<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>404 - Halaman Tidak Ditemukan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <main class="min-h-screen flex items-center justify-center px-4">
        <section class="w-full max-w-lg rounded-xl bg-white p-8 shadow-lg text-center">
            <p class="text-sm font-semibold tracking-wide text-indigo-600">ERROR 404</p>
            <h1 class="mt-2 text-3xl font-bold text-gray-900">Halaman tidak ditemukan</h1>
            <p class="mt-3 text-gray-600">
                Maaf, halaman yang Anda cari tidak tersedia atau sudah dipindahkan.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a
                    href="{{ url('/') }}"
                    class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-500 transition"
                >
                    Kembali ke Beranda
                </a>

                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
                    >
                        Ke Dashboard
                    </a>
                @endauth
            </div>
        </section>
    </main>
</body>
</html>
