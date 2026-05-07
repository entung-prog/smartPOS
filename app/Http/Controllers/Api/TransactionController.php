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

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['user', 'customer', 'items.product'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $transactions,
        ]);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['user', 'customer', 'items.product']);

        return response()->json([
            'success' => true,
            'data'    => $transaction,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id'   => 'nullable|exists:customers,id',
            'paid'          => 'required|integer|min:0',
            'note'          => 'nullable|string',
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

                // Broadcast stock update
                broadcast(new StockUpdated($item['product']))->toOthers();
            }

            DB::commit();

            $transaction->load(['user', 'customer', 'items.product']);

            // Broadcast transaksi baru
            broadcast(new TransactionCreated($transaction))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil.',
                'data'    => $transaction,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
