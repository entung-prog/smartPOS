<div class="flex flex-col lg:flex-row gap-4 sm:gap-6 h-full">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="fixed top-4 right-4 left-4 sm:left-auto z-50 bg-emerald-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button wire:click="dismissSuccess" class="text-white/80 hover:text-white shrink-0">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 left-4 sm:left-auto z-50 bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">{{ session('error') }}</span>
        </div>
    @endif

    {{-- LEFT: Product Grid --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Search & Filter --}}
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mb-3 sm:mb-4">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari produk atau SKU..."
                    class="w-full pl-10 pr-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                >
            </div>
            <select
                wire:model.live="categoryFilter"
                class="px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            >
                <option value="">Semua Kategori</option>
                @foreach($this->categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 overflow-y-auto flex-1 pb-4 lg:pb-0" style="max-height: calc(100vh - 260px);">
            @forelse($this->products as $product)
                <div
                    wire:click="addToCart({{ $product->id }})"
                    class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 cursor-pointer hover:shadow-lg hover:border-indigo-300 transition-all duration-200 group"
                >
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-2 sm:p-4 mb-2 sm:mb-3 flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-10 sm:h-10 text-indigo-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-xs sm:text-sm truncate">{{ $product->name }}</h3>
                    <p class="text-indigo-600 font-bold text-sm sm:text-base mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <div class="flex items-center justify-between mt-1 sm:mt-2">
                        <span class="text-[10px] sm:text-xs text-gray-500 truncate">{{ $product->sku }}</span>
                        <span class="text-[10px] sm:text-xs px-1.5 sm:px-2 py-0.5 rounded-full {{ $product->stock <= 5 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $product->stock }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-sm">Tidak ada produk ditemukan</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: Cart Panel --}}
    <div class="w-full lg:w-80 xl:w-96 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm lg:sticky lg:top-4 lg:self-start">
        {{-- Cart Header --}}
        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                Keranjang
                @if(count($cart) > 0)
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ count($cart) }}</span>
                @endif
            </h2>
            @if(count($cart) > 0)
                <button wire:click="clearCart" class="text-xs text-red-500 hover:text-red-700 font-medium">Kosongkan</button>
            @endif
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto px-4 sm:px-5 py-2 sm:py-3" style="max-height: 250px;">
            @forelse($cart as $item)
                <div class="flex items-center gap-2 sm:gap-3 py-2 sm:py-3 border-b border-gray-50 last:border-0">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-xs sm:text-sm text-gray-800 truncate">{{ $item['name'] }}</h4>
                        <p class="text-[10px] sm:text-xs text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['qty'] }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="decrementQty({{ $item['id'] }})" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 text-xs sm:text-sm font-bold transition">−</button>
                        <span class="w-6 sm:w-8 text-center text-xs sm:text-sm font-semibold">{{ $item['qty'] }}</span>
                        <button wire:click="incrementQty({{ $item['id'] }})" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 text-xs sm:text-sm font-bold transition">+</button>
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-gray-800 whitespace-nowrap">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                    <button wire:click="removeItem({{ $item['id'] }})" class="text-red-400 hover:text-red-600 transition shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    <p class="text-xs sm:text-sm">Klik produk untuk menambahkan</p>
                </div>
            @endforelse
        </div>

        {{-- Cart Footer --}}
        <div class="border-t border-gray-100 px-4 sm:px-5 py-3 sm:py-4 space-y-2 sm:space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 font-medium text-sm">Total</span>
                <span class="text-xl sm:text-2xl font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Uang Bayar</label>
                <input
                    type="number"
                    wire:model.live="paid"
                    placeholder="0"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base sm:text-lg font-semibold"
                >
            </div>

            <div class="flex justify-between items-center py-2 px-3 rounded-lg {{ $change >= 0 ? 'bg-emerald-50' : 'bg-red-50' }}">
                <span class="text-xs sm:text-sm font-medium {{ $change >= 0 ? 'text-emerald-700' : 'text-red-700' }}">Kembalian</span>
                <span class="text-base sm:text-lg font-bold {{ $change >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                    Rp {{ number_format(abs($change), 0, ',', '.') }}
                    @if($change < 0) <span class="text-xs">(kurang)</span> @endif
                </span>
            </div>

            <button
                wire:click="checkout"
                wire:loading.attr="disabled"
                @if(empty($cart) || $paid < $total) disabled @endif
                class="w-full py-2.5 sm:py-3 px-6 rounded-xl font-bold text-white text-sm sm:text-lg transition-all duration-200
                    {{ empty($cart) || $paid < $total
                        ? 'bg-gray-300 cursor-not-allowed'
                        : 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl active:scale-[0.98]'
                    }}"
            >
                <span wire:loading.remove wire:target="checkout">✅ Checkout</span>
                <span wire:loading wire:target="checkout">⏳ Processing...</span>
            </button>
        </div>
    </div>
</div>
