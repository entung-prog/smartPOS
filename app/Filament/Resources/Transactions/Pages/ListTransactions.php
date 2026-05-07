<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Transaksi'),
            \Filament\Actions\Action::make('report')
                ->label('Laporan')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->url(TransactionResource::getUrl('report')),
        ];
    }
}
