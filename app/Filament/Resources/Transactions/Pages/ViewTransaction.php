<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

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
                    ]),

                Section::make('Pembayaran')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('total')->label('Total')->money('IDR'),
                        TextEntry::make('paid')->label('Dibayar')->money('IDR'),
                        TextEntry::make('change')->label('Kembalian')->money('IDR'),
                    ]),
            ]);
    }
}
