<?php

namespace App\Livewire;

use App\Events\StockUpdated;
use App\Events\TransactionCreated;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
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
        if (! $product) return;

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] < $product->stock) {
                $this->cart[$productId]['qty']++;
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
        if (isset($this->cart[$productId]) && $this->cart[$productId]['qty'] < $this->cart[$productId]['stock']) {
            $this->cart[$productId]['qty']++;
            $this->calculateTotal();
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

    public function updatedPaid(): void
    {
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
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'total'   => $this->total,
                'paid'    => $this->paid,
                'change'  => $this->change,
                'status'  => 'completed',
            ]);

            foreach ($this->cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'qty'            => $item['qty'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['price'] * $item['qty'],
                ]);

                $product->decrement('stock', $item['qty']);

                broadcast(new StockUpdated($product->fresh()))->toOthers();
            }

            DB::commit();

            broadcast(new TransactionCreated($transaction))->toOthers();

            $this->lastTransactionId = $transaction->id;
            $this->showSuccess = true;
            $this->cart = [];
            $this->total = 0;
            $this->paid = 0;
            $this->change = 0;

            session()->flash('success', 'Transaksi berhasil! Kembalian: Rp ' . number_format($transaction->change, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }

    public function dismissSuccess(): void
    {
        $this->showSuccess = false;
    }

    public function render()
    {
        return view('livewire.pos-terminal');
    }
}
