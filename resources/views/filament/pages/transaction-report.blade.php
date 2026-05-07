<x-filament-panels::page>
    {{-- Filter Form --}}
    <div class="mb-6">
        {{ $this->form }}
    </div>

    {{-- Period Label --}}
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
            📊 Laporan: {{ $this->periodLabel }}
        </h3>
    </div>

    @php $report = $this->reportData; @endphp

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        {{-- Total Transaksi --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Transaksi</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($report['totalTransactions']) }}</p>
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($report['completedCount']) }}</p>
                </div>
            </div>
        </div>

        {{-- Dibatalkan --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-x-circle class="w-5 h-5 text-red-600 dark:text-red-400" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Dibatalkan</p>
                    <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ number_format($report['cancelledCount']) }}</p>
                </div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-banknotes class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($report['totalRevenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Rata-rata --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-calculator class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Rata-rata / Trx</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($report['averageTransaction'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Top Products --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <x-heroicon-o-trophy class="w-5 h-5 text-amber-500" />
                    Top 10 Produk Terlaris
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Produk</th>
                            <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Qty</th>
                            <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($report['topProducts'] as $i => $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-2.5">
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ number_format($product->total_qty) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right font-semibold text-gray-800 dark:text-gray-200">
                                    Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Cashiers --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <x-heroicon-o-user-group class="w-5 h-5 text-indigo-500" />
                    Performa Kasir
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Kasir</th>
                            <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Transaksi</th>
                            <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($report['topCashiers'] as $i => $cashier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-2.5 font-medium text-gray-800 dark:text-gray-200">{{ $cashier->name }}</td>
                                <td class="px-4 py-2.5 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        {{ number_format($cashier->total_transactions) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right font-semibold text-gray-800 dark:text-gray-200">
                                    Rp {{ number_format($cashier->total_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daily Breakdown --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-blue-500" />
                Rincian Harian
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Tanggal</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Jumlah Transaksi</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Pendapatan</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($report['dailyData'] as $day)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-4 py-2.5 font-medium text-gray-800 dark:text-gray-200">
                                {{ \Carbon\Carbon::parse($day->date)->translatedFormat('l, d M Y') }}
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    {{ number_format($day->count) }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-right font-semibold text-gray-800 dark:text-gray-200">
                                Rp {{ number_format($day->revenue, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2.5 text-right text-gray-600 dark:text-gray-400">
                                Rp {{ $day->count > 0 ? number_format(round($day->revenue / $day->count), 0, ',', '.') : '0' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                Belum ada transaksi dalam periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($report['dailyData']->count() > 0)
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr class="font-bold">
                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200">TOTAL</td>
                            <td class="px-4 py-3 text-right text-gray-800 dark:text-gray-200">{{ number_format($report['dailyData']->sum('count')) }}</td>
                            <td class="px-4 py-3 text-right text-gray-800 dark:text-gray-200">Rp {{ number_format($report['dailyData']->sum('revenue'), 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">
                                Rp {{ $report['dailyData']->sum('count') > 0 ? number_format(round($report['dailyData']->sum('revenue') / $report['dailyData']->sum('count')), 0, ',', '.') : '0' }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                <x-heroicon-o-clock class="w-5 h-5 text-gray-500" />
                Transaksi Terbaru (20 terakhir)
            </h3>
            <a href="{{ \App\Filament\Resources\TransactionResource::getUrl('index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Waktu</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Kasir</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Pelanggan</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Item</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-600 dark:text-gray-300">Total</th>
                        <th class="px-4 py-2 text-center font-medium text-gray-600 dark:text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($report['recentTransactions'] as $trx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">{{ $trx->id }}</td>
                            <td class="px-4 py-2.5 text-gray-700 dark:text-gray-300">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-2.5 text-gray-800 dark:text-gray-200">{{ $trx->user?->name ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-gray-600 dark:text-gray-400">{{ $trx->customer?->name ?? 'Umum' }}</td>
                            <td class="px-4 py-2.5">
                                <div class="space-y-0.5 max-w-xs">
                                    @foreach($trx->items->take(3) as $item)
                                        <div class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                            {{ $item->product?->name ?? 'Produk dihapus' }}
                                            <span class="text-gray-400 dark:text-gray-500">× {{ $item->qty }}</span>
                                        </div>
                                    @endforeach
                                    @if($trx->items->count() > 3)
                                        <span class="text-xs text-gray-400">+{{ $trx->items->count() - 3 }} lainnya</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-right font-semibold text-gray-800 dark:text-gray-200">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $trx->status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $trx->status === 'completed' ? 'Selesai' : 'Batal' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
