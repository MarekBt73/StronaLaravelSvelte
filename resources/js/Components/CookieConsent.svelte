<script>
    /**
     * Cookie Consent Component - GDPR/RODO Compliant
     *
     * Zgodny z:
     * - RODO (Rozporządzenie 2016/679)
     * - Dyrektywa ePrivacy (2009/136/WE)
     * - Wytyczne EROD (European Data Protection Board)
     */

    let { onConsentChange = () => {} } = $props();

    // Stan komponentu
    let showBanner = $state(false);
    let showDetails = $state(false);
    let isLoading = $state(true);

    // Nasluchuj eventu otwierania ustawien
    $effect(() => {
        if (typeof window !== 'undefined') {
            const handler = () => {
                showBanner = true;
                showDetails = true;
            };
            window.addEventListener('openCookieSettings', handler);
            return () => window.removeEventListener('openCookieSettings', handler);
        }
    });

    // Kategorie ciasteczek
    let consent = $state({
        essential: true,      // Zawsze wymagane - nie można wyłączyć
        analytics: false,     // Statystyki i analityka
        marketing: false,     // Reklamy i marketing
    });

    // Sprawdź zapisaną zgodę przy starcie
    $effect(() => {
        if (typeof window !== 'undefined') {
            const savedConsent = localStorage.getItem('cookieConsent');
            if (savedConsent) {
                try {
                    const parsed = JSON.parse(savedConsent);
                    consent = { ...consent, ...parsed };
                    onConsentChange(consent);
                    showBanner = false;
                } catch {
                    showBanner = true;
                }
            } else {
                showBanner = true;
            }
            isLoading = false;
        }
    });

    // Zapisz zgodę
    function saveConsent(newConsent) {
        consent = { ...consent, ...newConsent };
        localStorage.setItem('cookieConsent', JSON.stringify(consent));
        localStorage.setItem('cookieConsentDate', new Date().toISOString());
        showBanner = false;
        showDetails = false;
        onConsentChange(consent);

        // Dispatch event for other scripts (e.g., Google Analytics)
        window.dispatchEvent(new CustomEvent('cookieConsentChanged', { detail: consent }));
    }

    // Zaakceptuj wszystkie
    function acceptAll() {
        saveConsent({
            essential: true,
            analytics: true,
            marketing: true,
        });
    }

    // Odrzuć opcjonalne
    function rejectOptional() {
        saveConsent({
            essential: true,
            analytics: false,
            marketing: false,
        });
    }

    // Zapisz wybrane ustawienia
    function saveSelected() {
        saveConsent(consent);
    }

    // Kategorie z opisami
    const categories = [
        {
            id: 'essential',
            name: 'Niezbędne',
            description: 'Te pliki cookie są konieczne do prawidłowego działania strony. Nie można ich wyłączyć.',
            examples: 'Sesja użytkownika, preferencje językowe, zabezpieczenia',
            required: true,
        },
        {
            id: 'analytics',
            name: 'Analityczne',
            description: 'Pomagają nam zrozumieć, jak użytkownicy korzystają ze strony. Dane są anonimowe.',
            examples: 'Liczba odwiedzin, źródła ruchu, najpopularniejsze strony',
            required: false,
        },
        {
            id: 'marketing',
            name: 'Marketingowe',
            description: 'Wykorzystywane do wyświetlania spersonalizowanych reklam i śledzenia ich skuteczności.',
            examples: 'Reklamy, remarketing, social media widgets',
            required: false,
        },
    ];
</script>

{#if !isLoading && showBanner}
    <!-- Backdrop -->
    <div
        class="fixed inset-0 bg-black/50 z-[200]"
        aria-hidden="true"
    ></div>

    <!-- Cookie Banner -->
    <div
        class="fixed bottom-0 left-0 right-0 z-[201] bg-white shadow-2xl
               border-t border-gray-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cookie-title"
        aria-describedby="cookie-desc"
    >
        <div class="max-w-6xl mx-auto p-4 sm:p-6">
            {#if !showDetails}
                <!-- Prosty widok -->
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-8">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-6 h-6 text-medical-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <h2 id="cookie-title" class="text-lg font-bold text-gray-900">
                                Twoja prywatność jest dla nas ważna
                            </h2>
                        </div>
                        <p id="cookie-desc" class="text-sm text-gray-600 leading-relaxed">
                            Używamy plików cookie, aby zapewnić najlepsze doświadczenia na naszej stronie.
                            Niektóre są niezbędne, inne pomagają nam ulepszać usługi i dostosowywać treści.
                            Możesz zaakceptować wszystkie lub dostosować swoje preferencje.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 lg:flex-shrink-0">
                        <button
                            type="button"
                            onclick={() => showDetails = true}
                            class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300
                                   rounded-lg hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2
                                   focus-visible:ring-medical-500 transition-colors order-3 sm:order-1"
                        >
                            Dostosuj
                        </button>
                        <button
                            type="button"
                            onclick={rejectOptional}
                            class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300
                                   rounded-lg hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2
                                   focus-visible:ring-medical-500 transition-colors order-2"
                        >
                            Tylko niezbędne
                        </button>
                        <button
                            type="button"
                            onclick={acceptAll}
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-medical-600
                                   rounded-lg hover:bg-medical-700 focus-visible:outline-none focus-visible:ring-2
                                   focus-visible:ring-medical-500 focus-visible:ring-offset-2 transition-colors order-1 sm:order-3"
                        >
                            Akceptuj wszystkie
                        </button>
                    </div>
                </div>
            {:else}
                <!-- Szczegółowy widok -->
                <div class="max-h-[70vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Ustawienia prywatności</h2>
                        <button
                            type="button"
                            onclick={() => showDetails = false}
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500"
                            aria-label="Wróć"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>
                    </div>

                    <p class="text-sm text-gray-600 mb-6">
                        Poniżej możesz szczegółowo określić, na jakie kategorie plików cookie wyrażasz zgodę.
                        Pliki niezbędne są zawsze aktywne, ponieważ strona nie może bez nich funkcjonować.
                    </p>

                    <!-- Kategorie -->
                    <div class="space-y-4 mb-6">
                        {#each categories as category}
                            <div class="border border-gray-200 rounded-lg p-4 {category.required ? 'bg-gray-50' : ''}">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-semibold text-gray-900">{category.name}</h3>
                                            {#if category.required}
                                                <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-600 rounded-full">
                                                    Wymagane
                                                </span>
                                            {/if}
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">{category.description}</p>
                                        <p class="text-xs text-gray-400">
                                            <strong>Przykłady:</strong> {category.examples}
                                        </p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input
                                            type="checkbox"
                                            checked={consent[category.id]}
                                            onchange={(e) => { consent[category.id] = e.target.checked; }}
                                            disabled={category.required}
                                            class="sr-only peer"
                                        />
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4
                                                    peer-focus:ring-medical-500/20 rounded-full peer
                                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                                    after:bg-white after:border-gray-300 after:border after:rounded-full
                                                    after:h-5 after:w-5 after:transition-all
                                                    peer-checked:bg-medical-600 peer-disabled:opacity-50
                                                    peer-disabled:cursor-not-allowed">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        {/each}
                    </div>

                    <!-- Przyciski -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <button
                            type="button"
                            onclick={rejectOptional}
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300
                                   rounded-lg hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2
                                   focus-visible:ring-medical-500 transition-colors"
                        >
                            Tylko niezbędne
                        </button>
                        <button
                            type="button"
                            onclick={saveSelected}
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-medical-600
                                   rounded-lg hover:bg-medical-700 focus-visible:outline-none focus-visible:ring-2
                                   focus-visible:ring-medical-500 focus-visible:ring-offset-2 transition-colors"
                        >
                            Zapisz moje wybory
                        </button>
                        <button
                            type="button"
                            onclick={acceptAll}
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-green-600
                                   rounded-lg hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2
                                   focus-visible:ring-green-500 focus-visible:ring-offset-2 transition-colors"
                        >
                            Akceptuj wszystkie
                        </button>
                    </div>

                    <!-- Link do polityki -->
                    <div class="mt-4 text-center">
                        <a
                            href="/polityka-prywatnosci"
                            class="text-sm text-medical-600 hover:text-medical-700 underline
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500 rounded"
                        >
                            Dowiedz się więcej w naszej Polityce Prywatności
                        </a>
                    </div>
                </div>
            {/if}
        </div>
    </div>
{/if}
