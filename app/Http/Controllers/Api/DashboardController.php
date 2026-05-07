<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $today = now()->toDateString();

        $todayTransactions = Transaction::whereDate('created_at', $today)->get();

        $data = [
            'today' => [
                'transactions_count' => $todayTransactions->count(),
                'revenue'            => $todayTransactions->sum('total'),
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
            'revenue_last_7_days' => collect(range(6, 0))->map(function ($daysAgo) {
                $date = now()->subDays($daysAgo)->toDateString();
                return [
                    'date'    => $date,
                    'revenue' => Transaction::whereDate('created_at', $date)->sum('total'),
                    'count'   => Transaction::whereDate('created_at', $date)->count(),
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }
}
