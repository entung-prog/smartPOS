<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Transaksi')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('id')->label('ID Transaksi'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default     => 'warning',
                            }),
                        TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d M Y, H:i'),
                        TextEntry::make('user.name')->label('Kasir'),
                        TextEntry::make('customer.name')
                            ->label('Pelanggan')
                            ->default('Umum'),
                        TextEntry::make('note')
                            ->label('Catatan')
                            ->default('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Pembayaran')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('total')->label('Total')->money('IDR'),
                        TextEntry::make('paid')->label('Dibayar')->money('IDR'),
                        TextEntry::make('change')->label('Kembalian')->money('IDR'),
                    ]),

                Section::make('Detail Item')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Produk')
                                    ->default('Produk dihapus'),
                                TextEntry::make('qty')
                                    ->label('Qty')
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('price')
                                    ->label('Harga Satuan')
                                    ->money('IDR'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('IDR'),
                            ]),
                    ]),
            ]);
    }
}
