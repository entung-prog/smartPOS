<?php

namespace App\Livewire;

use App\Events\StockUpdated;
use App\Events\TransactionCreated;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PosTerminal extends Component
{
    public string $search = '';
    public string $categoryFilter = '';
    public array $cart = [];
    public int $total = 0;
    public int $paid = 0;
    public int $change = 0;
    public bool $showSuccess = false;
    public ?int $lastTransactionId = null;

    // Receipt data
    public bool $showReceipt = false;
    public array $receiptData = [];

    public function getProductsProperty()
    {
        $query = Product::where('stock', '>', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        return $query->orderBy('name')->get();
    }

    public function getCategoriesProperty()
    {
        return Product::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) {
            $this->dispatch('notify', type: 'error', message: 'Produk tidak ditemukan.');
            return;
        }

        if ($product->stock <= 0) {
            $this->dispatch('notify', type: 'error', message: "Stok {$product->name} habis.");
            return;
        }

        if (isset($this->cart[$productId])) {
            // Refresh stock from DB to prevent stale stock check
            if ($this->cart[$productId]['qty'] < $product->stock) {
                $this->cart[$productId]['qty']++;
                // Update stock & price from DB (keep data fresh)
                $this->cart[$productId]['stock'] = $product->stock;
                $this->cart[$productId]['price'] = $product->price;
            } else {
                $this->dispatch('notify', type: 'warning', message: "Stok {$product->name} tidak mencukupi.");
            }
        } else {
            $this->cart[$productId] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'qty'   => 1,
            ];
        }

        $this->calculateTotal();
    }

    public function incrementQty(int $productId): void
    {
        if (! isset($this->cart[$productId])) return;

        // Refresh stock from DB to prevent stale stock check
        $product = Product::find($productId);
        if (! $product) {
            // Product was deleted — remove from cart
            unset($this->cart[$productId]);
            $this->calculateTotal();
            $this->dispatch('notify', type: 'error', message: 'Produk telah dihapus dari sistem.');
            return;
        }

        if ($this->cart[$productId]['qty'] < $product->stock) {
            $this->cart[$productId]['qty']++;
            $this->cart[$productId]['stock'] = $product->stock;
            $this->calculateTotal();
        } else {
            $this->dispatch('notify', type: 'warning', message: "Stok {$product->name} tidak mencukupi.");
        }
    }

    public function decrementQty(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']--;
            if ($this->cart[$productId]['qty'] <= 0) {
                unset($this->cart[$productId]);
            }
            $this->calculateTotal();
        }
    }

    public function removeItem(int $productId): void
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->total = 0;
        $this->paid = 0;
        $this->change = 0;
    }

    public function calculateTotal(): void
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['qty'];
        }
        $this->change = $this->paid - $this->total;
    }

    public function updatedPaid($value): void
    {
        // Handle empty input / non-numeric values gracefully
        $this->paid = is_numeric($value) ? (int) $value : 0;
        $this->change = $this->paid - $this->total;
    }

    public function checkout(): void
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang kosong!');
            return;
        }

        if ($this->paid < $this->total) {
            session()->flash('error', 'Uang bayar kurang dari total!');
            return;
        }

        DB::beginTransaction();

        try {
            $receiptItems = [];
            $recalculatedTotal = 0;

            // First pass: validate all products and recalculate with current prices
            foreach ($this->cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                // BUG FIX: Null check — product may have been deleted
                if (! $product) {
                    throw new \RuntimeException("Produk '{$item['name']}' sudah tidak tersedia di sistem.");
                }

                if ($product->stock < $item['qty']) {
                    throw new \RuntimeException("Stok produk '{$product->name}' tidak mencukupi. Tersisa: {$product->stock}.");
                }

                $recalculatedTotal += $product->price * $item['qty'];
            }

            // Use recalculated total with current DB prices
            $actualChange = $this->paid - $recalculatedTotal;
            if ($actualChange < 0) {
                throw new \RuntimeException('Uang bayar kurang dari total setelah penyesuaian harga terbaru.');
            }

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'total'   => $recalculatedTotal,
                'paid'    => $this->paid,
                'change'  => $actualChange,
                'status'  => 'completed',
            ]);

            foreach ($this->cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'qty'            => $item['qty'],
                    'price'          => $product->price,
                    'subtotal'       => $product->price * $item['qty'],
                ]);

                $product->decrement('stock', $item['qty']);

                try {
                    broadcast(new StockUpdated($product->fresh()))->toOthers();
                } catch (\Exception $e) {
                    // Broadcasting failure should not break the transaction
                    Log::warning('StockUpdated broadcast failed', ['error' => $e->getMessage()]);
                }

                $receiptItems[] = [
                    'name'     => $product->name,
                    'qty'      => $item['qty'],
                    'price'    => $product->price,
                    'subtotal' => $product->price * $item['qty'],
                ];
            }

            DB::commit();

            try {
                broadcast(new TransactionCreated($transaction))->toOthers();
            } catch (\Exception $e) {
                Log::warning('TransactionCreated broadcast failed', ['error' => $e->getMessage()]);
            }

            // Build receipt data
            $this->receiptData = [
                'id'       => $transaction->id,
                'date'     => now()->format('d/m/Y H:i'),
                'cashier'  => auth()->user()->name,
                'items'    => $receiptItems,
                'total'    => $recalculatedTotal,
                'paid'     => $this->paid,
                'change'   => $actualChange,
            ];

            $this->lastTransactionId = $transaction->id;
            $this->showSuccess = true;
            $this->showReceipt = true;
            $this->cart = [];
            $this->total = 0;
            $this->paid = 0;
            $this->change = 0;

            session()->flash('success', 'Transaksi berhasil! Kembalian: Rp ' . number_format($actualChange, 0, ',', '.'));

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('POS Checkout failed', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'cart'    => $this->cart,
            ]);

            $message = $e instanceof \RuntimeException
                ? $e->getMessage()
                : 'Transaksi gagal. Silakan coba lagi atau hubungi admin.';

            session()->flash('error', $message);
        }
    }

    public function dismissSuccess(): void
    {
        $this->showSuccess = false;
    }

    public function closeReceipt(): void
    {
        $this->showReceipt = false;
    }

    public function render()
    {
        return view('livewire.pos-terminal');
    }
}
