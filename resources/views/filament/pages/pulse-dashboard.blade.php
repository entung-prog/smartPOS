<x-filament-panels::page>
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Laravel Pulse</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Monitor performa aplikasi, query lambat, exceptions, dan queue jobs.
                </p>
            </div>
            <a
                href="/pulse"
                target="_blank"
                class="fi-btn fi-btn-size-md inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Buka Pulse Dashboard
            </a>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Server</p>
                <p class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-1">CPU, Memory, Storage</p>
                <p class="text-xs text-gray-400 mt-1">Lihat di Pulse →</p>
            </div>
            <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Slow Queries</p>
                <p class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-1">Database</p>
                <p class="text-xs text-gray-400 mt-1">Lihat di Pulse →</p>
            </div>
            <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Exceptions</p>
                <p class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-1">Error Tracking</p>
                <p class="text-xs text-gray-400 mt-1">Lihat di Pulse →</p>
            </div>
            <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Queue Jobs</p>
                <p class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-1">Background Tasks</p>
                <p class="text-xs text-gray-400 mt-1">Lihat di Pulse →</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
