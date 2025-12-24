<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Informacje systemowe
        </x-slot>

        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <span class="text-blue-600 dark:text-blue-400 font-bold text-xs">PHP</span>
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">PHP</span>
                </div>
                <span class="font-medium text-gray-900 dark:text-white">{{ $phpVersion }}</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                        <x-heroicon-o-cube class="w-4 h-4 text-red-600 dark:text-red-400" />
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Laravel</span>
                </div>
                <span class="font-medium text-gray-900 dark:text-white">{{ $laravelVersion }}</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center">
                        <x-heroicon-o-fire class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Filament</span>
                </div>
                <span class="font-medium text-gray-900 dark:text-white">{{ $filamentVersion }}</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                        <x-heroicon-o-server class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Storage</span>
                </div>
                <span class="font-medium text-gray-900 dark:text-white">{{ $storageUsed }}</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <x-heroicon-o-cog-6-tooth class="w-4 h-4 text-green-600 dark:text-green-400" />
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Srodowisko</span>
                </div>
                <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $environment }}</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <x-heroicon-o-bug-ant class="w-4 h-4 text-gray-600 dark:text-gray-400" />
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Debug</span>
                </div>
                <span class="font-medium {{ config('app.debug') ? 'text-warning-600' : 'text-success-600' }}">
                    {{ $debugMode }}
                </span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
