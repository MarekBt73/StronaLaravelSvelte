<script>
    import { inertia } from '@inertiajs/svelte';

    let { children } = $props();

    // Mobile menu state
    let mobileMenuOpen = $state(false);

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
        // Prevent body scroll when menu is open
        if (mobileMenuOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    // Close menu on escape key
    function handleKeydown(event) {
        if (event.key === 'Escape' && mobileMenuOpen) {
            mobileMenuOpen = false;
            document.body.style.overflow = '';
        }
    }

    // Close menu when clicking a link
    function closeMenu() {
        mobileMenuOpen = false;
        document.body.style.overflow = '';
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
        <nav
            id="mobile-menu"
            class="fixed top-0 right-0 bottom-0 w-full max-w-sm bg-white z-50 lg:hidden
                   shadow-2xl overflow-y-auto"
            aria-label="Menu mobilne"
            role="dialog"
            aria-modal="true"
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
        </nav>
    {/if}
</header>

<!-- Main Content -->
<main id="main-content" tabindex="-1">
    {@render children()}
</main>

<!-- Footer -->
<footer class="bg-medical-900 text-white" role="contentinfo">
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
