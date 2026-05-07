<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT: Product Grid (2 cols) --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Search --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="🔍 Cari produk..."
                    class="fi-input w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3" style="max-height: 65vh; overflow-y: auto;">
                @foreach($this->products as $product)
                    <button
                        wire:click="addToCart({{ $product->id }})"
                        class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-left hover:ring-primary-500 hover:shadow-md transition-all duration-200 cursor-pointer"
                    >
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3 mb-3 flex items-center justify-center">
                            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-sm text-gray-800 dark:text-gray-200 truncate">{{ $product->name }}</h3>
                        <p class="text-primary-600 dark:text-primary-400 font-bold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <div class="mt-2">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $product->stock <= 5 ? 'bg-danger-50 text-danger-600 dark:bg-danger-500/10 dark:text-danger-400' : 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400' }}">
                                Stok: {{ $product->stock }}
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Cart (1 col) --}}
        <div class="lg:col-span-1">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sticky top-4">
                {{-- Cart Header --}}
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        🛒 Keranjang
                        @if(count($cart) > 0)
                            <span class="bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400 text-xs font-semibold px-2 py-0.5 rounded-full">{{ count($cart) }}</span>
                        @endif
                    </h2>
                </div>

                {{-- Cart Items --}}
                <div class="px-5 py-3 space-y-2 overflow-y-auto" style="max-height: 280px;">
                    @forelse($cart as $item)
                        <div class="flex items-center justify-between gap-2 py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-sm text-gray-800 dark:text-gray-200 truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['qty'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                            <button wire:click="removeItem({{ $item['id'] }})" class="text-danger-500 hover:text-danger-700 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @empty
                        <p class="text-center py-8 text-gray-400 text-sm">Keranjang kosong</p>
                    @endforelse
                </div>

                {{-- Payment --}}
                <div class="border-t border-gray-100 dark:border-gray-800 px-5 py-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">Total</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <input
                        type="number"
                        wire:model.live="paid"
                        placeholder="Uang Bayar"
                        class="fi-input w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-lg font-semibold focus:ring-primary-500 focus:border-primary-500"
                    >

                    <div class="flex justify-between items-center py-2 px-3 rounded-lg {{ $change >= 0 ? 'bg-success-50 dark:bg-success-500/10' : 'bg-danger-50 dark:bg-danger-500/10' }}">
                        <span class="text-sm font-medium {{ $change >= 0 ? 'text-success-700 dark:text-success-400' : 'text-danger-700 dark:text-danger-400' }}">Kembalian</span>
                        <span class="text-lg font-bold {{ $change >= 0 ? 'text-success-700 dark:text-success-400' : 'text-danger-700 dark:text-danger-400' }}">
                            Rp {{ number_format(abs($change), 0, ',', '.') }}
                        </span>
                    </div>

                    <button
                        wire:click="checkout"
                        class="fi-btn fi-btn-size-lg w-full justify-center rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all"
                    >
                        ✅ Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>