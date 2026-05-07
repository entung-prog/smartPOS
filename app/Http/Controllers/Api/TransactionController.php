<?php

namespace App\Http\Controllers\Api;

use App\Events\StockUpdated;
use App\Events\TransactionCreated;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Transaction::with(['user', 'customer', 'items.product'])
                ->latest();

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            // Period filters: day, week, month, year
            if ($request->filled('period')) {
                $now = now();
                match ($request->period) {
                    'day'   => $query->whereDate('created_at', $now->toDateString()),
                    'week'  => $query->whereBetween('created_at', [$now->startOfWeek(), $now->copy()->endOfWeek()]),
                    'month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
                    'year'  => $query->whereYear('created_at', $now->year),
                    default => null,
                };
            }

            $transactions = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data'    => $transactions,
            ]);
        } catch (\Exception $e) {
            Log::error('TransactionController@index failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data transaksi.',
            ], 500);
        }
    }

    public function show(Transaction $transaction): JsonResponse
    {
        try {
            $transaction->load(['user', 'customer', 'items.product']);

            return response()->json([
                'success' => true,
                'data'    => $transaction,
            ]);
        } catch (\Exception $e) {
            Log::error('TransactionController@show failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail transaksi.',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id'   => 'nullable|exists:customers,id',
            'paid'          => 'required|integer|min:0',
            'note'          => 'nullable|string|max:500',
            'items'         => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk '{$product->name}' tidak mencukupi. Tersisa: {$product->stock}",
                    ], 422);
                }

                $subtotal = $product->price * $item['qty'];
                $total   += $subtotal;

                $itemsData[] = [
                    'product'  => $product,
                    'qty'      => $item['qty'],
                    'price'    => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $change = $validated['paid'] - $total;

            if ($change < 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Uang yang dibayar kurang dari total belanja.',
                ], 422);
            }

            $transaction = Transaction::create([
                'user_id'     => $request->user()->id,
                'customer_id' => $validated['customer_id'] ?? null,
                'total'       => $total,
                'paid'        => $validated['paid'],
                'change'      => $change,
                'note'        => $validated['note'] ?? null,
                'status'      => 'completed',
            ]);

            foreach ($itemsData as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product']->id,
                    'qty'            => $item['qty'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['subtotal'],
                ]);

                // Kurangi stok
                $item['product']->decrement('stock', $item['qty']);
                $item['product']->refresh();

                // Broadcast stock update (don't let broadcasting failure break transaction)
                try {
                    broadcast(new StockUpdated($item['product']))->toOthers();
                } catch (\Exception $e) {
                    Log::warning('StockUpdated broadcast failed', ['error' => $e->getMessage()]);
                }
            }

            DB::commit();

            $transaction->load(['user', 'customer', 'items.product']);

            // Broadcast transaksi baru
            try {
                broadcast(new TransactionCreated($transaction))->toOthers();
            } catch (\Exception $e) {
                Log::warning('TransactionCreated broadcast failed', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil.',
                'data'    => $transaction,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TransactionController@store failed', [
                'user_id' => $request->user()?->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses transaksi.',
            ], 500);
        }
    }

    public function update(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'status'      => 'sometimes|string|in:completed,cancelled',
            'note'        => 'nullable|string|max:500',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        try {
            // If cancelling, restore product stock
            if (isset($validated['status']) && $validated['status'] === 'cancelled' && $transaction->status !== 'cancelled') {
                DB::beginTransaction();

                foreach ($transaction->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->qty);

                        try {
                            broadcast(new StockUpdated($item->product->fresh()))->toOthers();
                        } catch (\Exception $e) {
                            Log::warning('StockUpdated broadcast failed on cancel', ['error' => $e->getMessage()]);
                        }
                    }
                }

                $transaction->update($validated);
                DB::commit();
            } else {
                $transaction->update($validated);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diupdate.',
                'data'    => $transaction->fresh()->load(['user', 'customer', 'items.product']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TransactionController@update failed', ['error' => $e->getMessage(), 'transaction_id' => $transaction->id]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate transaksi.',
            ], 500);
        }
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        try {
            if ($transaction->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi yang sudah selesai tidak bisa dihapus. Gunakan pembatalan (cancel) sebagai gantinya.',
                ], 422);
            }

            DB::beginTransaction();

            // Delete related items first
            $transaction->items()->delete();
            $transaction->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TransactionController@destroy failed', ['error' => $e->getMessage(), 'transaction_id' => $transaction->id]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi.',
            ], 500);
        }
    }
}
