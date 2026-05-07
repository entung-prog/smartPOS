<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TransactionResource::class;

    protected string $view = 'filament.pages.transaction-report';

    protected static ?string $title = 'Laporan Transaksi';

    // Filter state
    public ?string $period = 'month';
    public ?string $dateFrom = null;
    public ?string $dateUntil = null;

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateUntil = now()->toDateString();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('period')
                    ->label('Periode')
                    ->options([
                        'day'    => 'Hari Ini',
                        'week'   => 'Minggu Ini',
                        'month'  => 'Bulan Ini',
                        'year'   => 'Tahun Ini',
                        'custom' => 'Custom Range',
                    ])
                    ->default('month')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        match ($state) {
                            'day'    => $this->setDateRange(now(), now()),
                            'week'   => $this->setDateRange(now()->startOfWeek(), now()->endOfWeek()),
                            'month'  => $this->setDateRange(now()->startOfMonth(), now()->endOfMonth()),
                            'year'   => $this->setDateRange(now()->startOfYear(), now()->endOfYear()),
                            default  => null,
                        };
                    }),

                DatePicker::make('dateFrom')
                    ->label('Dari Tanggal')
                    ->visible(fn () => $this->period === 'custom')
                    ->live(),

                DatePicker::make('dateUntil')
                    ->label('Sampai Tanggal')
                    ->visible(fn () => $this->period === 'custom')
                    ->live(),
            ])
            ->columns(3);
    }

    protected function setDateRange(Carbon $from, Carbon $to): void
    {
        $this->dateFrom = $from->toDateString();
        $this->dateUntil = $to->toDateString();
    }

    public function getReportDataProperty(): array
    {
        $query = Transaction::query();

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateUntil) {
            $query->whereDate('created_at', '<=', $this->dateUntil);
        }

        $totalTransactions = (clone $query)->count();
        $completedCount = (clone $query)->where('status', 'completed')->count();
        $cancelledCount = (clone $query)->where('status', 'cancelled')->count();
        $totalRevenue = (clone $query)->where('status', 'completed')->sum('total');
        $averageTransaction = $completedCount > 0 ? round($totalRevenue / $completedCount) : 0;

        // Daily breakdown
        $dailyData = (clone $query)
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, COALESCE(SUM(total), 0) as revenue')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Top products
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_items.product_id')
            ->where('transactions.status', 'completed')
            ->when($this->dateFrom, fn ($q) => $q->whereDate('transactions.created_at', '>=', $this->dateFrom))
            ->when($this->dateUntil, fn ($q) => $q->whereDate('transactions.created_at', '<=', $this->dateUntil))
            ->selectRaw('products.name, products.sku, SUM(transaction_items.qty) as total_qty, SUM(transaction_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Top cashiers
        $topCashiers = Transaction::query()
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->when($this->dateFrom, fn ($q) => $q->whereDate('transactions.created_at', '>=', $this->dateFrom))
            ->when($this->dateUntil, fn ($q) => $q->whereDate('transactions.created_at', '<=', $this->dateUntil))
            ->where('transactions.status', 'completed')
            ->selectRaw('users.name, COUNT(*) as total_transactions, SUM(transactions.total) as total_revenue')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'customer', 'items.product'])
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateUntil, fn ($q) => $q->whereDate('created_at', '<=', $this->dateUntil))
            ->latest()
            ->limit(20)
            ->get();

        return [
            'totalTransactions'   => $totalTransactions,
            'completedCount'      => $completedCount,
            'cancelledCount'      => $cancelledCount,
            'totalRevenue'        => $totalRevenue,
            'averageTransaction'  => $averageTransaction,
            'dailyData'           => $dailyData,
            'topProducts'         => $topProducts,
            'topCashiers'         => $topCashiers,
            'recentTransactions'  => $recentTransactions,
        ];
    }

    public function getPeriodLabelProperty(): string
    {
        return match ($this->period) {
            'day'    => 'Hari Ini (' . now()->format('d M Y') . ')',
            'week'   => 'Minggu Ini (' . now()->startOfWeek()->format('d M') . ' - ' . now()->endOfWeek()->format('d M Y') . ')',
            'month'  => 'Bulan Ini (' . now()->format('F Y') . ')',
            'year'   => 'Tahun Ini (' . now()->format('Y') . ')',
            'custom' => ($this->dateFrom ? Carbon::parse($this->dateFrom)->format('d M Y') : '...') . ' — ' . ($this->dateUntil ? Carbon::parse($this->dateUntil)->format('d M Y') : '...'),
            default  => '',
        };
    }
}
