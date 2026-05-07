<x-filament-panels::page>
    <style>
        .report-stack { display: grid; gap: 1rem; }
        .report-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: .75rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }
        .report-card-head {
            padding: .9rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: .95rem;
            font-weight: 600;
            color: #111827;
        }
        .report-card-body { padding: 1rem; }
        .report-period {
            display: inline-block;
            padding: .25rem .65rem;
            border-radius: 999px;
            background: #eef2ff;
            color: #4338ca;
            font-size: .75rem;
            font-weight: 600;
        }
        .report-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: .75rem;
        }
        .summary-item {
            border: 1px solid #e5e7eb;
            border-radius: .65rem;
            padding: .8rem;
            background: #fafafa;
        }
        .summary-label { font-size: .75rem; color: #6b7280; margin-bottom: .2rem; }
        .summary-value { font-size: 1.05rem; font-weight: 700; color: #111827; }
        .summary-value.success { color: #059669; }
        .summary-value.danger { color: #dc2626; }
        .report-two-col {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 1024px) {
            .report-two-col { grid-template-columns: 1fr 1fr; }
        }
        .report-table-wrap { overflow-x: auto; }
        .report-table {
            width: 100%;
            min-width: 640px;
            border-collapse: collapse;
            font-size: .85rem;
        }
        .report-table th,
        .report-table td {
            padding: .6rem .8rem;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            vertical-align: top;
            color: #1f2937;
            white-space: nowrap;
        }
        .report-table th { color: #6b7280; font-size: .78rem; font-weight: 600; }
        .report-table .text-right { text-align: right; }
        .report-table .text-center { text-align: center; }
        .report-link {
            color: #4f46e5;
            font-weight: 600;
            text-decoration: none;
        }
        .report-link:hover { text-decoration: underline; }
    </style>

    @php $report = $this->reportData; @endphp

    <div class="report-stack">
        <div class="report-card">
            <div class="report-card-body">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;margin-bottom:.9rem;">
                    <div>
                        <div style="font-size:1rem;font-weight:700;color:#111827;">Laporan Transaksi</div>
                        <div style="font-size:.84rem;color:#6b7280;">Ringkasan performa transaksi berdasarkan periode.</div>
                    </div>
                    <span class="report-period">{{ $this->periodLabel }}</span>
                </div>
                {{ $this->form }}
            </div>
        </div>

        <div class="report-summary">
            <div class="summary-item">
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ number_format($report['totalTransactions']) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Selesai</div>
                <div class="summary-value success">{{ number_format($report['completedCount']) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Dibatalkan</div>
                <div class="summary-value danger">{{ number_format($report['cancelledCount']) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($report['totalRevenue'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Rata-rata / Trx</div>
                <div class="summary-value">Rp {{ number_format($report['averageTransaction'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="report-two-col">
            <div class="report-card">
                <div class="report-card-head">Top 10 Produk Terlaris</div>
                <div class="report-table-wrap">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report['topProducts'] as $i => $product)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <div style="font-weight:600;">{{ $product->name }}</div>
                                        <div style="font-size:.75rem;color:#6b7280;">{{ $product->sku }}</div>
                                    </td>
                                    <td class="text-right">{{ number_format($product->total_qty) }}</td>
                                    <td class="text-right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="report-card">
                <div class="report-card-head">Performa Kasir</div>
                <div class="report-table-wrap">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kasir</th>
                                <th class="text-right">Transaksi</th>
                                <th class="text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report['topCashiers'] as $i => $cashier)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $cashier->name }}</td>
                                    <td class="text-right">{{ number_format($cashier->total_transactions) }}</td>
                                    <td class="text-right">Rp {{ number_format($cashier->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="report-card">
            <div class="report-card-head">Rincian Harian</div>
            <div class="report-table-wrap">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th class="text-right">Jumlah Transaksi</th>
                            <th class="text-right">Pendapatan</th>
                            <th class="text-right">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report['dailyData'] as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day->date)->translatedFormat('l, d M Y') }}</td>
                                <td class="text-right">{{ number_format($day->count) }}</td>
                                <td class="text-right">Rp {{ number_format($day->revenue, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ $day->count > 0 ? number_format(round($day->revenue / $day->count), 0, ',', '.') : '0' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Belum ada transaksi dalam periode ini</td></tr>
                        @endforelse
                    </tbody>
                    @if($report['dailyData']->count() > 0)
                        <tfoot>
                            <tr>
                                <td style="font-weight:700;">TOTAL</td>
                                <td class="text-right" style="font-weight:700;">{{ number_format($report['dailyData']->sum('count')) }}</td>
                                <td class="text-right" style="font-weight:700;">Rp {{ number_format($report['dailyData']->sum('revenue'), 0, ',', '.') }}</td>
                                <td class="text-right" style="font-weight:700;">Rp {{ $report['dailyData']->sum('count') > 0 ? number_format(round($report['dailyData']->sum('revenue') / $report['dailyData']->sum('count')), 0, ',', '.') : '0' }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="report-card">
            <div class="report-card-head" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                <span>Transaksi Terbaru (20 terakhir)</span>
                <a class="report-link" href="{{ \App\Filament\Resources\TransactionResource::getUrl('index') }}">Lihat Semua</a>
            </div>
            <div class="report-table-wrap">
                <table class="report-table" style="min-width:920px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report['recentTransactions'] as $trx)
                            <tr>
                                <td>{{ $trx->id }}</td>
                                <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $trx->user?->name ?? '-' }}</td>
                                <td>{{ $trx->customer?->name ?? 'Umum' }}</td>
                                <td>
                                    @foreach($trx->items->take(3) as $item)
                                        <div style="font-size:.78rem;">
                                            {{ $item->product?->name ?? 'Produk dihapus' }} x {{ $item->qty }}
                                        </div>
                                    @endforeach
                                    @if($trx->items->count() > 3)
                                        <div style="font-size:.75rem;color:#6b7280;">+{{ $trx->items->count() - 3 }} lainnya</div>
                                    @endif
                                </td>
                                <td class="text-right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $trx->status === 'completed' ? 'Selesai' : 'Batal' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
