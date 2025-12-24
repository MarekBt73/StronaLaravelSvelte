<script>
    import { inertia } from '@inertiajs/svelte';
    import { router } from '@inertiajs/svelte';
    import AccessibilityPanel from '../AccessibilityPanel.svelte';

    let { children } = $props();

    // Accessibility panel state
    let a11yPanelOpen = $state(false);

    // Mobile menu state
    let mobileMenuOpen = $state(false);

    // Search state
    let searchOpen = $state(false);
    let searchQuery = $state('');
    let searchResults = $state([]);
    let isSearching = $state(false);
    let searchInputRef = $state(null);
    let searchTimeout = $state(null);

    // Demo popup state
    let showDemoPopup = $state(false);

    // Show demo popup after 10 seconds (only if not dismissed before)
    $effect(() => {
        if (typeof window !== 'undefined') {
            const dismissed = localStorage.getItem('demoPopupDismissed');
            if (!dismissed) {
                const timer = setTimeout(() => {
                    showDemoPopup = true;
                }, 10000);
                return () => clearTimeout(timer);
            }
        }
    });

    // Close demo popup
    function closeDemoPopup() {
        showDemoPopup = false;
        localStorage.setItem('demoPopupDismissed', 'true');
    }

    const navigation = [
        { name: 'Strona główna', href: '/' },
        { name: 'O nas', href: '/o-nas' },
        { name: 'Usługi', href: '/uslugi' },
        { name: 'Lekarze', href: '/lekarze' },
        { name: 'Blog', href: '/blog' },
        { name: 'Kontakt', href: '/kontakt' },
    ];

    // Toggle mobile menu
    function toggleMobileMenu() {
        mobileMenuOpen = !mobileMenuOpen;
        if (mobileMenuOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    // Toggle search
    function toggleSearch() {
        searchOpen = !searchOpen;
        if (searchOpen) {
            document.body.style.overflow = 'hidden';
            setTimeout(() => searchInputRef?.focus(), 100);
        } else {
            document.body.style.overflow = '';
            searchQuery = '';
            searchResults = [];
        }
    }

    // Close search
    function closeSearch() {
        searchOpen = false;
        document.body.style.overflow = '';
        searchQuery = '';
        searchResults = [];
    }

    // Handle search input
    function handleSearchInput() {
        if (searchTimeout) clearTimeout(searchTimeout);

        if (searchQuery.length < 2) {
            searchResults = [];
            return;
        }

        isSearching = true;
        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/api/search?q=${encodeURIComponent(searchQuery)}`);
                const data = await response.json();
                searchResults = data.results || [];
            } catch (error) {
                console.error('Search error:', error);
                searchResults = [];
            } finally {
                isSearching = false;
            }
        }, 300);
    }

    // Navigate to result
    function goToResult(url) {
        closeSearch();
        router.visit(url);
    }

    // Close menu on escape key
    function handleKeydown(event) {
        if (event.key === 'Escape') {
            if (searchOpen) {
                closeSearch();
            } else if (mobileMenuOpen) {
                mobileMenuOpen = false;
                document.body.style.overflow = '';
            }
        }
    }

    // Close menu when clicking a link
    function closeMenu() {
        mobileMenuOpen = false;
        document.body.style.overflow = '';
    }

    // Get type icon
    function getTypeIcon(type) {
        switch (type) {
            case 'article': return '📰';
            case 'service': return '🏥';
            case 'page': return '📄';
            default: return '🔗';
        }
    }
</script>

<svelte:window on:keydown={handleKeydown} />

<!-- Skip to main content - WCAG 2.1 -->
<a
    href="#main-content"
    class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100]
           focus:px-4 focus:py-2 focus:bg-medical-600 focus:text-white focus:rounded-lg
           focus:outline-none focus:ring-4 focus:ring-medical-500/50"
>
    Przejdź do treści głównej
</a>

<!-- Top Bar - Improved mobile layout -->
<div class="bg-medical-800 text-white text-sm" role="banner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0">
            <!-- Contact info -->
            <div class="flex items-center justify-center sm:justify-start gap-4 sm:gap-6">
                <a
                    href="tel:+48221234567"
                    class="hover:text-medical-200 transition-colors flex items-center gap-1"
                    aria-label="Zadzwoń do nas: +48 22 123 45 67"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>+48 22 123 45 67</span>
                </a>
                <a
                    href="mailto:kontakt@medvita.pl"
                    class="hover:text-medical-200 transition-colors hidden md:flex items-center gap-1"
                    aria-label="Napisz do nas: kontakt@medvita.pl"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>kontakt@medvita.pl</span>
                </a>
            </div>
            <!-- Hours -->
            <div class="text-medical-200 text-center sm:text-right text-xs sm:text-sm">
                <span class="hidden sm:inline">Pon-Pt: 8:00-20:00 | Sob: 9:00-14:00</span>
                <span class="sm:hidden">Pon-Pt: 8-20 | Sob: 9-14</span>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <!-- Logo -->
            <a
                href="/"
                use:inertia
                class="flex items-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500 rounded-lg"
                aria-label="MedVita - Strona główna"
            >
                <div class="w-10 h-10 bg-medical-600 rounded-lg flex items-center justify-center" aria-hidden="true">
                    <span class="text-white font-bold text-xl">M</span>
                </div>
                <div>
                    <span class="text-xl font-bold text-medical-800">MedVita</span>
                    <span class="hidden sm:inline text-sm text-gray-500 ml-1">Centrum Zdrowia</span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8" aria-label="Nawigacja główna">
                {#each navigation as item}
                    <a
                        href={item.href}
                        use:inertia
                        class="text-gray-600 hover:text-medical-600 font-medium transition-colors
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               focus-visible:ring-offset-2 rounded px-2 py-1"
                    >
                        {item.name}
                    </a>
                {/each}
            </nav>

            <!-- Right side buttons -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Search button -->
                <button
                    type="button"
                    onclick={toggleSearch}
                    class="p-2 rounded-lg text-gray-600 hover:text-medical-600 hover:bg-gray-100
                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                           transition-colors"
                    aria-label="Otwórz wyszukiwarkę"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- CTA Button - hidden on very small screens when menu button is shown -->
                <a
                    href="/rezerwacja"
                    use:inertia
                    class="hidden sm:inline-flex px-4 sm:px-6 py-2 sm:py-2.5 bg-medical-600 text-white
                           font-semibold rounded-lg hover:bg-medical-700 transition-colors text-sm sm:text-base
                           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-medical-500/50"
                >
                    Umów wizytę
                </a>

                <!-- Mobile menu button -->
                <button
                    type="button"
                    class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-medical-600 hover:bg-gray-100
                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500"
                    onclick={toggleMobileMenu}
                    aria-expanded={mobileMenuOpen}
                    aria-controls="mobile-menu"
                    aria-label={mobileMenuOpen ? 'Zamknij menu' : 'Otwórz menu'}
                >
                    {#if mobileMenuOpen}
                        <!-- Close icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    {:else}
                        <!-- Hamburger icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    {/if}
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    {#if mobileMenuOpen}
        <!-- Backdrop -->
        <div
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            onclick={closeMenu}
            aria-hidden="true"
        ></div>

        <!-- Menu panel -->
        <div
            id="mobile-menu"
            class="fixed top-0 right-0 bottom-0 w-full max-w-sm bg-white z-50 lg:hidden
                   shadow-2xl overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-label="Menu mobilne"
        >
            <!-- Menu header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <span class="text-lg font-semibold text-medical-800">Menu</span>
                <button
                    type="button"
                    class="p-2 rounded-lg text-gray-600 hover:text-medical-600 hover:bg-gray-100
                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500"
                    onclick={closeMenu}
                    aria-label="Zamknij menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation links -->
            <div class="py-4">
                {#each navigation as item}
                    <a
                        href={item.href}
                        use:inertia
                        onclick={closeMenu}
                        class="block px-6 py-4 text-lg text-gray-700 hover:text-medical-600
                               hover:bg-medical-50 transition-colors
                               focus-visible:outline-none focus-visible:bg-medical-50 focus-visible:text-medical-600"
                    >
                        {item.name}
                    </a>
                {/each}
            </div>

            <!-- CTA in mobile menu -->
            <div class="p-4 border-t border-gray-100">
                <a
                    href="/rezerwacja"
                    use:inertia
                    onclick={closeMenu}
                    class="block w-full px-6 py-4 bg-medical-600 text-white font-semibold
                           rounded-lg text-center hover:bg-medical-700 transition-colors
                           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-medical-500/50"
                >
                    Umów wizytę online
                </a>
            </div>

            <!-- Contact info in mobile menu -->
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <p class="text-sm text-gray-500 mb-3">Kontakt:</p>
                <a
                    href="tel:+48221234567"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-medical-600"
                >
                    <svg class="w-5 h-5 text-medical-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    +48 22 123 45 67
                </a>
                <a
                    href="mailto:kontakt@medvita.pl"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-medical-600"
                >
                    <svg class="w-5 h-5 text-medical-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    kontakt@medvita.pl
                </a>
                <p class="mt-3 text-sm text-gray-500">
                    Pon-Pt: 8:00-20:00 | Sob: 9:00-14:00
                </p>
            </div>
        </div>
    {/if}

    <!-- Search Modal -->
    {#if searchOpen}
        <!-- Backdrop -->
        <div
            class="fixed inset-0 bg-black/60 z-[60]"
            onclick={closeSearch}
            aria-hidden="true"
        ></div>

        <!-- Search Panel -->
        <div
            class="fixed inset-x-0 top-0 z-[70] bg-white shadow-2xl max-h-[80vh] overflow-hidden"
            role="dialog"
            aria-modal="true"
            aria-label="Wyszukiwarka"
        >
            <div class="max-w-3xl mx-auto p-4">
                <!-- Search Input -->
                <div class="relative">
                    <label for="global-search" class="sr-only">Szukaj w serwisie</label>
                    <input
                        type="search"
                        id="global-search"
                        bind:this={searchInputRef}
                        bind:value={searchQuery}
                        oninput={handleSearchInput}
                        placeholder="Szukaj artykułów, usług, lekarzy..."
                        class="w-full px-4 py-4 pl-12 pr-12 text-lg rounded-xl border-2 border-gray-200
                               focus:border-medical-500 focus:outline-none focus:ring-4 focus:ring-medical-500/20
                               transition-all"
                    />
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button
                        type="button"
                        onclick={closeSearch}
                        class="absolute right-4 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500 rounded"
                        aria-label="Zamknij wyszukiwarkę"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Search Results -->
                <div class="mt-4 max-h-[60vh] overflow-y-auto">
                    {#if isSearching}
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-8 h-8 mx-auto animate-spin text-medical-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2">Szukam...</p>
                        </div>
                    {:else if searchQuery.length >= 2 && searchResults.length === 0}
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Nie znaleziono wyników dla "<strong>{searchQuery}</strong>"</p>
                            <p class="text-sm mt-1">Spróbuj innych słów kluczowych</p>
                        </div>
                    {:else if searchResults.length > 0}
                        <ul class="divide-y divide-gray-100" role="listbox">
                            {#each searchResults as result}
                                <li>
                                    <button
                                        type="button"
                                        onclick={() => goToResult(result.url)}
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 focus:bg-medical-50
                                               focus:outline-none transition-colors rounded-lg group"
                                        role="option"
                                    >
                                        <div class="flex items-start gap-3">
                                            <span class="text-2xl" aria-hidden="true">{getTypeIcon(result.type)}</span>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-medium text-gray-900 group-hover:text-medical-600 transition-colors">
                                                        {result.title}
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                        {result.category}
                                                    </span>
                                                </div>
                                                {#if result.excerpt}
                                                    <p class="text-sm text-gray-500 line-clamp-2">{result.excerpt}</p>
                                                {/if}
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-medical-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </button>
                                </li>
                            {/each}
                        </ul>
                    {:else if searchQuery.length < 2}
                        <div class="text-center py-8 text-gray-500">
                            <p>Wpisz co najmniej 2 znaki, aby wyszukać</p>
                            <p class="text-sm mt-2 text-gray-400">Przeszukuj artykuły, usługi i strony</p>
                        </div>
                    {/if}
                </div>

                <!-- Keyboard hint -->
                <div class="mt-4 pt-4 border-t border-gray-100 text-center text-sm text-gray-400">
                    Naciśnij <kbd class="px-2 py-1 bg-gray-100 rounded text-gray-600">ESC</kbd> aby zamknąć
                </div>
            </div>
        </div>
    {/if}
</header>

<!-- Main Content -->
<main id="main-content" tabindex="-1">
    {@render children()}
</main>

<!-- Footer -->
<footer class="bg-medical-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-medical-600 rounded-lg flex items-center justify-center" aria-hidden="true">
                        <span class="text-white font-bold">M</span>
                    </div>
                    <span class="text-lg font-bold">MedVita</span>
                </div>
                <p class="text-medical-200 text-sm">
                    Kompleksowa opieka medyczna w Warszawie.
                    3 placówki, 12 specjalistów, rezerwacja online 24/7.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h2 class="font-semibold mb-4">Szybkie linki</h2>
                <ul class="space-y-2 text-sm text-medical-200" role="list">
                    <li><a href="/o-nas" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">O nas</a></li>
                    <li><a href="/uslugi" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">Usługi</a></li>
                    <li><a href="/lekarze" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">Nasi lekarze</a></li>
                    <li><a href="/blog" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">Blog</a></li>
                    <li><a href="/kontakt" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">Kontakt</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h2 class="font-semibold mb-4">Kontakt</h2>
                <address class="space-y-2 text-sm text-medical-200 not-italic">
                    <p>ul. Zdrowa 15, 00-001 Warszawa</p>
                    <p>
                        <a href="tel:+48221234567" class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">
                            +48 22 123 45 67
                        </a>
                    </p>
                    <p>
                        <a href="mailto:kontakt@medvita.pl" class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">
                            kontakt@medvita.pl
                        </a>
                    </p>
                </address>
            </div>

            <!-- Legal -->
            <div>
                <h2 class="font-semibold mb-4">Informacje prawne</h2>
                <ul class="space-y-2 text-sm text-medical-200" role="list">
                    <li><a href="/regulamin" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">Regulamin</a></li>
                    <li><a href="/polityka-prywatnosci" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">Polityka prywatności</a></li>
                    <li><a href="/rodo" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">RODO</a></li>
                    <li><a href="/dostepnosc" use:inertia class="hover:text-white transition-colors focus-visible:outline-none focus-visible:underline">Deklaracja dostępności</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-medical-800 mt-8 pt-8 text-center text-sm text-medical-300">
            <p>&copy; 2024 MedVita - Centrum Zdrowia. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</footer>

<!-- Accessibility Panel -->
<AccessibilityPanel bind:isOpen={a11yPanelOpen} />

<!-- Demo Site Popup -->
{#if showDemoPopup}
    <!-- Backdrop -->
    <div
        class="fixed inset-0 bg-black/70 z-[100] flex items-center justify-center p-4"
        onclick={closeDemoPopup}
        role="presentation"
    >
        <!-- Popup -->
        <div
            class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 sm:p-8 relative animate-popup"
            onclick={(e) => e.stopPropagation()}
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="demo-popup-title"
            aria-describedby="demo-popup-desc"
        >
            <!-- Close button -->
            <button
                type="button"
                onclick={closeDemoPopup}
                class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600
                       rounded-lg hover:bg-gray-100 transition-colors
                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500"
                aria-label="Zamknij"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Icon -->
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Content -->
            <h2 id="demo-popup-title" class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-3">
                Strona prezentacyjna
            </h2>
            <p id="demo-popup-desc" class="text-gray-600 text-center mb-6 leading-relaxed">
                To jest <strong>strona demonstracyjna</strong> przedstawiajaca mozliwosci nowoczesnej witryny dla kliniki medycznej.
                Wszystkie dane sa fikcyjne i sluza wylacznie celom prezentacyjnym.
            </p>

            <!-- CTA -->
            <div class="bg-gradient-to-r from-medical-50 to-blue-50 rounded-xl p-4 mb-6">
                <p class="text-sm text-gray-700 text-center mb-3">
                    Chcesz miec taka strone dla swojej firmy?
                </p>
                <a
                    href="https://becht.pl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-medical-600 text-white
                           font-semibold rounded-lg hover:bg-medical-700 transition-colors
                           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-medical-500/50"
                >
                    Zamow strone w becht.pl
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Dismiss button -->
            <button
                type="button"
                onclick={closeDemoPopup}
                class="w-full px-4 py-2 text-gray-500 hover:text-gray-700 text-sm
                       focus-visible:outline-none focus-visible:underline transition-colors"
            >
                Rozumiem, kontynuuj przegladanie
            </button>
        </div>
    </div>
{/if}

<style>
    @keyframes popup-appear {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .animate-popup {
        animation: popup-appear 0.3s ease-out;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
