<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class KasirDashboardController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();

        $todayTransactions = Transaction::where('user_id', $request->user()->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $todayRevenue = Transaction::where('user_id', $request->user()->id)
            ->whereDate('created_at', now()->toDateString())
            ->sum('total');

        return view('kasir.index', compact('products', 'todayTransactions', 'todayRevenue'));
    }

    public function riwayat(Request $request)
    {
        $transactions = Transaction::with(['items.product'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return view('kasir.riwayat', compact('transactions'));
    }
}
