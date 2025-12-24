<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Szybkie linki
        </x-slot>

        <div class="space-y-3">
            <a href="{{ url('/') }}" target="_blank"
               class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                    <x-heroicon-o-globe-alt class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Strona glowna</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Otworz frontend strony</p>
                </div>
                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 ml-auto text-gray-400" />
            </a>

            <a href="https://dpoczta.pl/" target="_blank"
               class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-success-100 dark:bg-success-900 flex items-center justify-center">
                    <x-heroicon-o-envelope class="w-5 h-5 text-success-600 dark:text-success-400" />
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Poczta e-mail</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">dpoczta.pl</p>
                </div>
                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 ml-auto text-gray-400" />
            </a>

            <a href="https://filamentphp.com/docs" target="_blank"
               class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-warning-100 dark:bg-warning-900 flex items-center justify-center">
                    <x-heroicon-o-book-open class="w-5 h-5 text-warning-600 dark:text-warning-400" />
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Dokumentacja</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Filament PHP docs</p>
                </div>
                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 ml-auto text-gray-400" />
            </a>

            <a href="{{ route('filament.admin.resources.media.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-info-100 dark:bg-info-900 flex items-center justify-center">
                    <x-heroicon-o-photo class="w-5 h-5 text-info-600 dark:text-info-400" />
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Biblioteka mediow</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Zarzadzaj plikami</p>
                </div>
                <x-heroicon-o-chevron-right class="w-4 h-4 ml-auto text-gray-400" />
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
