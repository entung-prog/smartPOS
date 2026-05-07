<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                Smart POS — Kasir
            </h2>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div class="flex items-center gap-4 sm:gap-6 text-sm flex-1 sm:flex-initial">
                    <div class="text-center">
                        <p class="text-xs sm:text-sm text-gray-500">Transaksi</p>
                        <p class="text-lg sm:text-xl font-bold text-indigo-600">{{ $todayTransactions }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs sm:text-sm text-gray-500">Pendapatan</p>
                        <p class="text-lg sm:text-xl font-bold text-emerald-600">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>
                <a href="{{ route('kasir.riwayat') }}" class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs sm:text-sm font-medium transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-3 sm:py-4">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            @livewire('pos-terminal')
        </div>
    </div>
</x-app-layout>
