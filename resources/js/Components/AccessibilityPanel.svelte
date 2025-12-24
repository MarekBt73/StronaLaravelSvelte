<script>
    import { onMount } from 'svelte';

    let { isOpen = $bindable(false) } = $props();

    // Accessibility settings with defaults
    let settings = $state({
        fontSize: 'normal',      // small, normal, large
        contrast: 'normal',      // normal, high
        letterCase: 'normal',    // normal, uppercase
        theme: 'light'           // light, dark
    });

    // Load settings from localStorage on mount (onMount prevents infinite loop)
    onMount(() => {
        const saved = localStorage.getItem('a11ySettings');
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                settings.fontSize = parsed.fontSize || 'normal';
                settings.contrast = parsed.contrast || 'normal';
                settings.letterCase = parsed.letterCase || 'normal';
                settings.theme = parsed.theme || 'light';
            } catch (e) {
                console.error('Failed to parse a11y settings', e);
            }
        }
        applySettings();
    });

    // Save and apply settings - call this function manually instead of using $effect
    function saveAndApplySettings() {
        if (typeof window !== 'undefined') {
            localStorage.setItem('a11ySettings', JSON.stringify(settings));
            applySettings();
        }
    }

    // Apply settings to document
    function applySettings() {
        const root = document.documentElement;
        const body = document.body;

        // Font size
        root.classList.remove('a11y-font-small', 'a11y-font-normal', 'a11y-font-large');
        root.classList.add(`a11y-font-${settings.fontSize}`);

        // Contrast
        root.classList.remove('a11y-contrast-normal', 'a11y-contrast-high');
        root.classList.add(`a11y-contrast-${settings.contrast}`);

        // Letter case
        root.classList.remove('a11y-case-normal', 'a11y-case-uppercase');
        root.classList.add(`a11y-case-${settings.letterCase}`);

        // Theme
        root.classList.remove('a11y-theme-light', 'a11y-theme-dark');
        root.classList.add(`a11y-theme-${settings.theme}`);
    }

    // Reset to defaults
    function resetSettings() {
        settings.fontSize = 'normal';
        settings.contrast = 'normal';
        settings.letterCase = 'normal';
        settings.theme = 'light';
        saveAndApplySettings();
    }

    // Toggle panel
    function togglePanel() {
        isOpen = !isOpen;
    }

    // Close panel
    function closePanel() {
        isOpen = false;
    }

    // Handle escape key
    function handleKeydown(event) {
        if (event.key === 'Escape' && isOpen) {
            closePanel();
        }
    }
</script>

<svelte:window on:keydown={handleKeydown} />

<!-- Accessibility Toggle Button -->
<button
    type="button"
    onclick={togglePanel}
    class="fixed bottom-4 right-4 z-[80] w-12 h-12 rounded-full bg-medical-600 text-white
           shadow-lg hover:bg-medical-700 focus-visible:outline-none focus-visible:ring-4
           focus-visible:ring-medical-500/50 transition-all hover:scale-105"
    aria-label="Otwórz panel dostępności"
    aria-expanded={isOpen}
    aria-controls="a11y-panel"
>
    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m9 5.197v-1a6 6 0 00-3-5.197" />
    </svg>
</button>

<!-- Accessibility Panel -->
{#if isOpen}
    <!-- Backdrop -->
    <div
        class="fixed inset-0 bg-black/50 z-[85]"
        onclick={closePanel}
        aria-hidden="true"
    ></div>

    <!-- Panel -->
    <div
        id="a11y-panel"
        class="fixed bottom-20 right-4 z-[90] w-80 bg-white rounded-xl shadow-2xl overflow-hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="a11y-panel-title"
    >
        <!-- Header -->
        <div class="bg-medical-600 text-white px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m9 5.197v-1a6 6 0 00-3-5.197" />
                </svg>
                <h2 id="a11y-panel-title" class="font-semibold">Dostepnosc</h2>
            </div>
            <button
                type="button"
                onclick={closePanel}
                class="p-1 rounded hover:bg-medical-500 transition-colors
                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
                aria-label="Zamknij panel"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-4 space-y-5">
            <!-- Font Size -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rozmiar czcionki
                </label>
                <div class="flex gap-2" role="radiogroup" aria-label="Rozmiar czcionki">
                    <button
                        type="button"
                        onclick={() => { settings.fontSize = 'small'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.fontSize === 'small'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.fontSize === 'small'}
                    >
                        <span class="text-xs">A</span> Maly
                    </button>
                    <button
                        type="button"
                        onclick={() => { settings.fontSize = 'normal'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.fontSize === 'normal'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.fontSize === 'normal'}
                    >
                        <span class="text-sm">A</span> Sredni
                    </button>
                    <button
                        type="button"
                        onclick={() => { settings.fontSize = 'large'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.fontSize === 'large'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.fontSize === 'large'}
                    >
                        <span class="text-base">A</span> Duzy
                    </button>
                </div>
            </div>

            <!-- Contrast -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kontrast kolorow
                </label>
                <div class="flex gap-2" role="radiogroup" aria-label="Kontrast kolorow">
                    <button
                        type="button"
                        onclick={() => { settings.contrast = 'normal'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.contrast === 'normal'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.contrast === 'normal'}
                    >
                        Normalny
                    </button>
                    <button
                        type="button"
                        onclick={() => { settings.contrast = 'high'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.contrast === 'high'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.contrast === 'high'}
                    >
                        Wysoki
                    </button>
                </div>
            </div>

            <!-- Letter Case -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Wielkosc liter
                </label>
                <div class="flex gap-2" role="radiogroup" aria-label="Wielkosc liter">
                    <button
                        type="button"
                        onclick={() => { settings.letterCase = 'normal'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.letterCase === 'normal'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.letterCase === 'normal'}
                    >
                        Normalna
                    </button>
                    <button
                        type="button"
                        onclick={() => { settings.letterCase = 'uppercase'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all uppercase
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.letterCase === 'uppercase'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.letterCase === 'uppercase'}
                    >
                        WIELKIE
                    </button>
                </div>
            </div>

            <!-- Theme -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tryb wyswietlania
                </label>
                <div class="flex gap-2" role="radiogroup" aria-label="Tryb wyswietlania">
                    <button
                        type="button"
                        onclick={() => { settings.theme = 'light'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.theme === 'light'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.theme === 'light'}
                    >
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Dzien
                    </button>
                    <button
                        type="button"
                        onclick={() => { settings.theme = 'dark'; saveAndApplySettings(); }}
                        class="flex-1 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               {settings.theme === 'dark'
                                   ? 'border-medical-600 bg-medical-50 text-medical-700'
                                   : 'border-gray-200 hover:border-gray-300 text-gray-600'}"
                        role="radio"
                        aria-checked={settings.theme === 'dark'}
                    >
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        Noc
                    </button>
                </div>
            </div>

            <!-- Reset Button -->
            <button
                type="button"
                onclick={resetSettings}
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600
                       hover:bg-gray-50 transition-colors
                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500"
            >
                Resetuj ustawienia
            </button>
        </div>

        <!-- Footer info -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
            <p class="text-xs text-gray-500 text-center">
                Ustawienia sa zapisywane w przegladarce
            </p>
        </div>
    </div>
{/if}
