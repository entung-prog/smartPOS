<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Transactions\Pages;
use App\Models\Transaction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Transaksi';

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Kasir')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->default('Umum')
                    ->searchable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('paid')
                    ->label('Dibayar')
                    ->money('IDR'),

                TextColumn::make('change')
                    ->label('Kembalian')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default     => 'warning',
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view'  => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
