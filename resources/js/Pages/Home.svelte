<script>
    const services = [
        { name: 'Lekarz rodzinny', icon: '🩺', description: 'Podstawowa opieka zdrowotna' },
        { name: 'Specjaliści', icon: '👨‍⚕️', description: 'Kardiolog, dermatolog i inni' },
        { name: 'Diagnostyka', icon: '🔬', description: 'USG, EKG, RTG' },
        { name: 'Laboratorium', icon: '🧪', description: 'Pełne badania krwi' },
    ];

    const features = [
        {
            icon: '⭐',
            title: 'Doświadczenie',
            description: 'Ponad 8 lat na rynku i 15 000 zadowolonych pacjentów rocznie.'
        },
        {
            icon: '🏥',
            title: 'Nowoczesność',
            description: 'Najnowszy sprzęt diagnostyczny i komfortowe gabinety.'
        },
        {
            icon: '📱',
            title: 'Wygoda',
            description: 'Rezerwacja online 24/7 i przypomnienia SMS o wizytach.'
        },
    ];
</script>

<svelte:head>
    <title>Strona Główna - MedVita Centrum Zdrowia</title>
    <meta name="description" content="MedVita - Centrum Zdrowia. Kompleksowa opieka medyczna w Warszawie." />
</svelte:head>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-medical-600 text-white">
        <div class="absolute inset-0 bg-gradient-to-r from-medical-700/90 to-medical-600/80"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    Kompleksowa opieka medyczna w Warszawie
                </h1>
                <p class="text-xl md:text-2xl text-medical-100 mb-8">
                    3 placówki | 12 specjalistów | Rezerwacja online 24/7
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button
                        type="button"
                        class="px-8 py-4 bg-white text-medical-600 font-semibold rounded-lg
                               hover:bg-medical-50 transition-colors duration-200
                               focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
                    >
                        Umów wizytę online
                    </button>
                    <button
                        type="button"
                        class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg
                               hover:bg-white/10 transition-colors duration-200
                               focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
                    >
                        Znajdź lekarza
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Nasze usługi
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Oferujemy szeroki zakres usług medycznych, od podstawowej opieki zdrowotnej po specjalistyczną diagnostykę.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                {#each services as service}
                    <article
                        class="bg-gray-50 rounded-xl p-6 text-center hover:shadow-lg
                               transition-shadow duration-200 cursor-pointer
                               focus-within:ring-2 focus-within:ring-medical-500"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-medical-100
                                    flex items-center justify-center">
                            <span class="text-2xl">{service.icon}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">{service.name}</h3>
                        <p class="text-sm text-gray-600">{service.description}</p>
                    </article>
                {/each}
            </div>
        </div>
    </section>

    <!-- Why Us -->
    <section class="py-16 lg:py-24 bg-medical-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Dlaczego MedVita?
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {#each features as feature}
                    <div class="bg-white rounded-xl p-8 shadow-sm">
                        <div class="text-4xl mb-4">{feature.icon}</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">{feature.title}</h3>
                        <p class="text-gray-600">{feature.description}</p>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-medical-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Zadbaj o swoje zdrowie już dziś
            </h2>
            <p class="text-xl text-medical-100 mb-8">
                Umów wizytę online lub zadzwoń do nas. Nasi specjaliści są do Twojej dyspozycji.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button
                    type="button"
                    class="px-8 py-4 bg-white text-medical-600 font-semibold rounded-lg
                           hover:bg-medical-50 transition-colors duration-200
                           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
                >
                    Umów wizytę
                </button>
                <a
                    href="tel:+48221234567"
                    class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg
                           hover:bg-white/10 transition-colors duration-200 inline-flex items-center justify-center
                           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
                >
                    Zadzwoń: +48 22 123 45 67
                </a>
            </div>
        </div>
    </section>
</main>
