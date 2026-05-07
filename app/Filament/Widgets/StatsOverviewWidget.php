<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->toDateString();

        $todayRevenue = Transaction::whereDate('created_at', $today)->sum('total');
        $todayCount   = Transaction::whereDate('created_at', $today)->count();
        $totalRevenue = Transaction::sum('total');

        // Pre-fetch 7-day data in a single query instead of 7 individual queries per stat
        $sevenDaysAgo = now()->subDays(6)->toDateString();
        $dailyData = Transaction::whereBetween('created_at', [$sevenDaysAgo . ' 00:00:00', now()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, COALESCE(SUM(total), 0) as revenue')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date');

        $chartCounts   = [];
        $chartRevenues = [];
        foreach (range(6, 0) as $d) {
            $date = now()->subDays($d)->toDateString();
            $chartCounts[]   = $dailyData[$date]->count ?? 0;
            $chartRevenues[] = $dailyData[$date]->revenue ?? 0;
        }

        return [
            Stat::make('Transaksi Hari Ini', $todayCount)
                ->description('Jumlah transaksi hari ini')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->chart($chartCounts),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Total pendapatan hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart($chartRevenues),

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
