<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Gambar')
                            ->disk('public')
                            ->size(120)
                            ->columnSpanFull(),
                        TextEntry::make('name')
                            ->label('Nama Produk'),
                        TextEntry::make('sku')
                            ->label('SKU')
                            ->copyable(),
                        TextEntry::make('category')
                            ->label('Kategori')
                            ->badge()
                            ->color('gray')
                            ->default('-'),
                        TextEntry::make('price')
                            ->label('Harga')
                            ->money('IDR'),
                        TextEntry::make('stock')
                            ->label('Stok')
                            ->badge()
                            ->color(fn (int $state): string => match (true) {
                                $state <= 0 => 'danger',
                                $state <= 5 => 'warning',
                                default => 'success',
                            }),
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->default('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
