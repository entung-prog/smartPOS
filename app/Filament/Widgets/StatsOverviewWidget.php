<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->toDateString();

        $todayRevenue = Transaction::whereDate('created_at', $today)->sum('total');
        $todayCount   = Transaction::whereDate('created_at', $today)->count();
        $totalRevenue = Transaction::sum('total');

        return [
            Stat::make('Transaksi Hari Ini', $todayCount)
                ->description('Jumlah transaksi hari ini')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->chart(
                    collect(range(6, 0))->map(fn ($d) =>
                        Transaction::whereDate('created_at', now()->subDays($d)->toDateString())->count()
                    )->toArray()
                ),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Total pendapatan hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart(
                    collect(range(6, 0))->map(fn ($d) =>
                        Transaction::whereDate('created_at', now()->subDays($d)->toDateString())->sum('total')
                    )->toArray()
                ),

            Stat::make('Total Produk', Product::count())
                ->description(Product::where('stock', '<=', 5)->count() . ' produk stok menipis')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),

            Stat::make('Total Pelanggan', Customer::count())
                ->description('Total pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Seluruh transaksi')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
        ];
    }
}
