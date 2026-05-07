<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat Transaksi
            </h2>
            <a href="{{ route('kasir.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Kasir
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">#</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Waktu</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Item</th>
                                <th class="px-6 py-3 text-right font-semibold text-gray-600">Total</th>
                                <th class="px-6 py-3 text-right font-semibold text-gray-600">Bayar</th>
                                <th class="px-6 py-3 text-right font-semibold text-gray-600">Kembali</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-500">{{ $trx->id }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-0.5">
                                            @foreach($trx->items as $item)
                                                <div class="text-gray-600 text-xs">
                                                    {{ $item->product->name ?? 'Produk dihapus' }}
                                                    <span class="text-gray-400">× {{ $item->qty }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-800">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right text-gray-600">Rp {{ number_format($trx->paid, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right text-gray-600">Rp {{ number_format($trx->change, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $trx->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $trx->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Belum ada transaksi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
