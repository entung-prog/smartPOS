<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->before(function (Transaction $record) {
                    if ($record->status === 'completed') {
                        Notification::make()
                            ->title('Tidak Bisa Dihapus')
                            ->body('Transaksi yang sudah selesai tidak bisa dihapus. Ubah status menjadi "Dibatalkan" terlebih dahulu.')
                            ->danger()
                            ->send();

                        $this->halt();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        // If status changed to cancelled, restore stock
        if (isset($data['status']) && $data['status'] === 'cancelled' && $record->status !== 'cancelled') {
            try {
                DB::beginTransaction();

                foreach ($record->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->qty);
                    }
                }

                DB::commit();

                Notification::make()
                    ->title('Stok Dikembalikan')
                    ->body('Stok produk telah dikembalikan karena transaksi dibatalkan.')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to restore stock on transaction cancel', [
                    'transaction_id' => $record->id,
                    'error' => $e->getMessage(),
                ]);

                Notification::make()
                    ->title('Gagal Mengembalikan Stok')
                    ->body('Terjadi kesalahan saat mengembalikan stok produk.')
                    ->danger()
                    ->send();
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
