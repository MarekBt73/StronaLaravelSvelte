<script>
    import { inertia } from '@inertiajs/svelte';

    let { children } = $props();

    const navigation = [
        { name: 'Strona główna', href: '/' },
        { name: 'O nas', href: '/o-nas' },
        { name: 'Usługi', href: '/uslugi' },
        { name: 'Lekarze', href: '/lekarze' },
        { name: 'Kontakt', href: '/kontakt' },
    ];
</script>

<!-- Top Bar -->
<div class="bg-medical-800 text-white text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="tel:+48221234567" class="hover:text-medical-200 transition-colors">
                    +48 22 123 45 67
                </a>
                <a href="mailto:kontakt@medvita.pl" class="hover:text-medical-200 transition-colors hidden sm:block">
                    kontakt@medvita.pl
                </a>
            </div>
            <div class="text-medical-200">
                Pon-Pt: 8:00-20:00 | Sob: 9:00-14:00
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <!-- Logo -->
            <a href="/" use:inertia class="flex items-center gap-2">
                <div class="w-10 h-10 bg-medical-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">M</span>
                </div>
                <div>
                    <span class="text-xl font-bold text-medical-800">MedVita</span>
                    <span class="hidden sm:inline text-sm text-gray-500 ml-1">Centrum Zdrowia</span>
                </div>
            </a>

            <!-- Navigation -->
            <nav class="hidden lg:flex items-center gap-8">
                {#each navigation as item}
                    <a
                        href={item.href}
                        use:inertia
                        class="text-gray-600 hover:text-medical-600 font-medium transition-colors"
                    >
                        {item.name}
                    </a>
                {/each}
            </nav>

            <!-- CTA Button -->
            <div class="flex items-center gap-4">
                <a
                    href="/rezerwacja"
                    use:inertia
                    class="px-6 py-2.5 bg-medical-600 text-white font-semibold rounded-lg
                           hover:bg-medical-700 transition-colors
                           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-medical-500/50"
                >
                    Umów wizytę
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main>
    {@render children()}
</main>

<!-- Footer -->
<footer class="bg-medical-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-medical-600 rounded-lg flex items-center justify-center">
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
                <h3 class="font-semibold mb-4">Szybkie linki</h3>
                <ul class="space-y-2 text-sm text-medical-200">
                    <li><a href="/o-nas" use:inertia class="hover:text-white transition-colors">O nas</a></li>
                    <li><a href="/uslugi" use:inertia class="hover:text-white transition-colors">Usługi</a></li>
                    <li><a href="/lekarze" use:inertia class="hover:text-white transition-colors">Nasi lekarze</a></li>
                    <li><a href="/kontakt" use:inertia class="hover:text-white transition-colors">Kontakt</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="font-semibold mb-4">Kontakt</h3>
                <ul class="space-y-2 text-sm text-medical-200">
                    <li>ul. Zdrowa 15, 00-001 Warszawa</li>
                    <li><a href="tel:+48221234567" class="hover:text-white transition-colors">+48 22 123 45 67</a></li>
                    <li><a href="mailto:kontakt@medvita.pl" class="hover:text-white transition-colors">kontakt@medvita.pl</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="font-semibold mb-4">Informacje prawne</h3>
                <ul class="space-y-2 text-sm text-medical-200">
                    <li><a href="/regulamin" use:inertia class="hover:text-white transition-colors">Regulamin</a></li>
                    <li><a href="/polityka-prywatnosci" use:inertia class="hover:text-white transition-colors">Polityka prywatności</a></li>
                    <li><a href="/rodo" use:inertia class="hover:text-white transition-colors">RODO</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-medical-800 mt-8 pt-8 text-center text-sm text-medical-300">
            <p>&copy; 2024 MedVita - Centrum Zdrowia. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</footer>
