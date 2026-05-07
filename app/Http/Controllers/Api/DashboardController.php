<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        try {
            $today = now()->toDateString();

            // Single query for today stats instead of fetching all records
            $todayStats = Transaction::whereDate('created_at', $today)
                ->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as revenue')
                ->first();

            // Single query for 7-day revenue chart (instead of 14 individual queries)
            $sevenDaysAgo = now()->subDays(6)->toDateString();
            $dailyStats = Transaction::whereBetween('created_at', [$sevenDaysAgo . ' 00:00:00', now()->endOfDay()])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count, COALESCE(SUM(total), 0) as revenue')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->get()
                ->keyBy('date');

            $revenueLast7Days = collect(range(6, 0))->map(function ($daysAgo) use ($dailyStats) {
                $date = now()->subDays($daysAgo)->toDateString();
                return [
                    'date'    => $date,
                    'revenue' => $dailyStats[$date]->revenue ?? 0,
                    'count'   => $dailyStats[$date]->count ?? 0,
                ];
            });

            $data = [
                'today' => [
                    'transactions_count' => $todayStats->count ?? 0,
                    'revenue'            => $todayStats->revenue ?? 0,
                ],
                'total' => [
                    'transactions' => Transaction::count(),
                    'products'     => Product::count(),
                    'customers'    => Customer::count(),
                    'users'        => User::count(),
                ],
                'low_stock_products' => Product::where('stock', '<=', 5)
                    ->orderBy('stock')
                    ->limit(10)
                    ->get(['id', 'name', 'sku', 'stock']),
                'recent_transactions' => Transaction::with(['user', 'customer'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'revenue_last_7_days' => $revenueLast7Days,
            ];

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('DashboardController@stats failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik dashboard.',
            ], 500);
        }
    }
}
