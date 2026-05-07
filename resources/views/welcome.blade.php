<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Smart POS — Sistem Point of Sale modern untuk mengelola transaksi, inventaris, dan laporan bisnis Anda secara efisien.">

        <title>Smart POS — Sistem Kasir Modern</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="min-h-screen bg-white">
            <div class="pointer-events-none fixed inset-0 -z-10">
                <div class="absolute -top-24 left-1/2 h-72 w-[44rem] -translate-x-1/2 rounded-full bg-gradient-to-r from-indigo-100 via-sky-100 to-purple-100 blur-3xl"></div>
                <div class="absolute bottom-0 right-[-10rem] h-72 w-72 rounded-full bg-indigo-50 blur-3xl"></div>
            </div>

            {{-- Navigation --}}
            <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <a href="/" class="flex items-center gap-3 font-semibold tracking-tight text-slate-900">    
                        </span>
                        <span>Smart POS</span>
                    </a>

                    <nav class="hidden items-center gap-1 sm:flex">
                        <a href="#fitur" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">Fitur</a>
                        <a href="#tentang" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">Tentang</a>

                        <div class="ml-2 flex items-center gap-2">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            Daftar
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </nav>

                    <div class="sm:hidden">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    Login
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </header>

            {{-- Hero --}}
            <main>
                <section class="mx-auto max-w-7xl px-4 pb-12 pt-14 sm:px-6 sm:pb-20 sm:pt-20 lg:px-8">
                    <div class="mx-auto grid max-w-5xl items-center gap-10 lg:grid-cols-2 lg:gap-14">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600 shadow-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span>Laravel {{ app()->version() }} • Filament • Livewire</span>
                            </div>

                            <h1 class="mt-5 text-balance text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                                Sistem kasir yang cepat, rapi, dan mudah digunakan.
                            </h1>
                            <p class="mt-4 text-pretty text-base leading-relaxed text-slate-600 sm:text-lg">
                                Smart POS membantu Anda mengelola transaksi, stok produk, pelanggan, dan laporan penjualan dalam satu dashboard yang bersih dan modern.
                            </p>

                            <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
                                @if (Route::has('login'))
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                                            Buka Dashboard
                                            <span class="ml-2 text-white/70">→</span>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            Mulai Sekarang
                                            <span class="ml-2 text-white/70">→</span>
                                        </a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                                                Buat Akun
                                            </a>
                                        @endif
                                    @endauth
                                @endif
                            </div>

                            <dl class="mt-9 grid grid-cols-2 gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Transaksi</dt>
                                    <dd class="mt-1 text-lg font-semibold text-slate-900">Unlimited</dd>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role</dt>
                                    <dd class="mt-1 text-lg font-semibold text-slate-900">Admin & Kasir</dd>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:col-span-1 col-span-2">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Real-time</dt>
                                    <dd class="mt-1 text-lg font-semibold text-slate-900">Reverb / WebSocket</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="relative">
                            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-rose-300"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                                    </div>
                                    <div class="text-xs font-medium text-slate-500">POS Terminal Preview</div>
                                </div>
                                <div class="p-6">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="rounded-2xl bg-slate-50 p-4">
                                            <div class="text-xs font-semibold text-slate-500">Keranjang</div>
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center justify-between rounded-xl bg-white p-3 shadow-sm">
                                                    <div class="text-sm font-medium text-slate-800">Americano</div>
                                                    <div class="text-sm font-semibold text-slate-900">24k</div>
                                                </div>
                                                <div class="flex items-center justify-between rounded-xl bg-white p-3 shadow-sm">
                                                    <div class="text-sm font-medium text-slate-800">Croissant</div>
                                                    <div class="text-sm font-semibold text-slate-900">18k</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rounded-2xl bg-slate-50 p-4">
                                            <div class="text-xs font-semibold text-slate-500">Ringkasan</div>
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center justify-between text-sm text-slate-700">
                                                    <span>Subtotal</span>
                                                    <span class="font-semibold text-slate-900">42k</span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm text-slate-700">
                                                    <span>Pajak</span>
                                                    <span class="font-semibold text-slate-900">0</span>
                                                </div>
                                                <div class="h-px bg-slate-200"></div>
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="font-semibold text-slate-900">Total</span>
                                                    <span class="font-bold text-slate-900">42k</span>
                                                </div>
                                                <div class="pt-2">
                                                    <div class="rounded-xl bg-indigo-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm">
                                                        Bayar
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mt-5 text-xs leading-relaxed text-slate-500">
                                        Preview ini hanya ilustrasi UI. Tampilan asli mengikuti data produk dan konfigurasi bisnis Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Features --}}
                <section id="fitur" class="border-t border-slate-200 bg-slate-50/60">
                    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
                        <div class="mx-auto max-w-2xl text-center">
                            <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Fitur inti untuk operasional harian</h2>
                            <p class="mt-4 text-base leading-relaxed text-slate-600">
                                Fokus pada alur kasir yang cepat, data yang rapi, dan monitoring yang mudah dipahami.
                            </p>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-tv" viewBox="0 0 16 16">
                                    <path d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5M13.991 3l.024.001a1.5 1.5 0 0 1 .538.143.76.76 0 0 1 .302.254c.067.1.145.277.145.602v5.991l-.001.024a1.5 1.5 0 0 1-.143.538.76.76 0 0 1-.254.302c-.1.067-.277.145-.602.145H2.009l-.024-.001a1.5 1.5 0 0 1-.538-.143.76.76 0 0 1-.302-.254C1.078 10.502 1 10.325 1 10V4.009l.001-.024a1.5 1.5 0 0 1 .143-.538.76.76 0 0 1 .254-.302C1.498 3.078 1.675 3 2 3zM14 2H2C0 2 0 4 0 4v6c0 2 2 2 2 2h12c2 0 2-2 2-2V4c0-2-2-2-2-2"/>
                                </svg>
                                <h3 class="mt-4 text-base font-semibold text-slate-900">POS Terminal</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Cari produk cepat, kelola keranjang, dan proses pembayaran tanpa ribet.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-boxes" viewBox="0 0 16 16">
                                    <path d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434zM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567zM7.5 9.933l-2.75 1.571v3.134l2.75-1.571zm1 3.134 2.75 1.571v-3.134L8.5 9.933zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567zm2.242-2.433V3.504L8.5 5.076V8.21zM7.5 8.21V5.076L4.75 3.504v3.134zM5.258 2.643 8 4.21l2.742-1.567L8 1.076zM15 9.933l-2.75 1.571v3.134L15 13.067zM3.75 14.638v-3.134L1 9.933v3.134z"/>
                                </svg>
                                <h3 class="mt-4 text-base font-semibold text-slate-900">Inventaris</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Stok otomatis berkurang saat transaksi dan mudah diaudit.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                                    <path d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a１ １ 0 0 １ １ １v3a１ １ 0 0 １-１ １H２a１ １ 0 0 １-１-１z"/>
                                </svg>
                                <h3 class="mt-4 text-base font-semibold text-slate-900">Riwayat Transaksi</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Detail transaksi lengkap, mudah dicari, dan siap jadi bahan laporan.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                                </svg>
                                <h3 class="mt-4 text-base font-semibold text-slate-900">Multi-Role</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Pisahkan akses Admin dan Kasir untuk operasional yang lebih aman.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
                                </svg>
                                <h3 class="mt-4 text-base font-semibold text-slate-900">Cetak Struk</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Cetak struk langsung dari browser dengan format yang rapi.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- About / CTA --}}
                <section id="tentang">
                    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
                        <div class="grid items-center gap-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm sm:p-10 lg:grid-cols-2">
                            <div>
                                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Siap dipakai, mudah dikembangkan</h2>
                                <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">
                                    Dibangun dengan Laravel, Filament, dan Livewire. Cocok untuk toko retail, coffee shop, restoran kecil, hingga multi-kasir.
                                </p>
                                <ul class="mt-6 space-y-3 text-sm text-slate-600">
                                    <li class="flex gap-3"><span class="mt-0.5 text-emerald-600">✓</span><span>UI admin rapi untuk produk, pelanggan, transaksi</span></li>
                                    <li class="flex gap-3"><span class="mt-0.5 text-emerald-600">✓</span><span>Real-time update (stok/transaksi) via WebSocket</span></li>
                                    <li class="flex gap-3"><span class="mt-0.5 text-emerald-600">✓</span><span>Struktur kode jelas untuk scaling fitur</span></li>
                                </ul>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-6">
                                <div class="text-sm font-semibold text-slate-900">Mulai sekarang</div>
                                <p class="mt-2 text-sm text-slate-600">Login untuk masuk dashboard. Jika belum punya akun, daftar dulu.</p>
                                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                                    @if (Route::has('login'))
                                        @auth
                                            <a href="{{ url('/dashboard') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 sm:w-auto">
                                                Buka Dashboard <span class="ml-2 text-white/70">→</span>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                                                Login <span class="ml-2 text-white/70">→</span>
                                            </a>
                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 sm:w-auto">
                                                    Daftar Akun
                                                </a>
                                            @endif
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-slate-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-10 text-sm text-slate-600 sm:px-6 lg:px-8">
                    <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
                        <p>© {{ date('Y') }} Smart POS</p>
                        <p class="text-slate-500">
                            Dibangun dengan
                            <a class="font-medium text-slate-700 hover:text-slate-900" href="https://laravel.com" target="_blank" rel="noreferrer">Laravel</a>
                            &
                            <a class="font-medium text-slate-700 hover:text-slate-900" href="https://filamentphp.com" target="_blank" rel="noreferrer">Filament</a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
