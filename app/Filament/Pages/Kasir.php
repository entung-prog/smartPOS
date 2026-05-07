<?php

namespace App\Filament\Pages;

use App\Events\StockUpdated;
use App\Events\TransactionCreated;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Kasir extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected string $view = 'filament.pages.kasir';

    protected static ?string $title = 'Kasir';

    protected static ?int $navigationSort = 1;

    public string $search = '';
    public array $cart = [];
    public int $total = 0;
    public int $paid = 0;
    public int $change = 0;

    public function getProductsProperty()
    {
        $query = Product::where('stock', '>', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        return $query->orderBy('name')->get();
    }

    public function addToCart($productId): void
    {
        $product = Product::find($productId);

        if (! $product) {
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] < $product->stock) {
                $this->cart[$productId]['qty']++;
            }
        } else {
            $this->cart[$productId] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->price,
                'qty'   => 1,
            ];
        }

        $this->calculateTotal();
    }

    public function removeItem($productId): void
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
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
            Notification::make()
                ->title('Keranjang kosong')
                ->warning()
                ->send();
            return;
        }

        if ($this->paid < $this->total) {
            Notification::make()
                ->title('Uang bayar kurang dari total!')
                ->danger()
                ->send();
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

            Notification::make()
                ->title('Transaksi berhasil!')
                ->body('Kembalian: Rp ' . number_format($transaction->change, 0, ',', '.'))
                ->success()
                ->send();

            $this->cart = [];
            $this->total = 0;
            $this->paid = 0;
            $this->change = 0;

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Transaksi gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}