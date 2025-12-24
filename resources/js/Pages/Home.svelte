<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';

    const services = [
        {
            name: 'Lekarz rodzinny',
            image: '/images/lekarz_rodzinny.png',
            description: 'Podstawowa opieka zdrowotna',
            href: '/uslugi#podstawowa-opieka-zdrowotna'
        },
        {
            name: 'Pediatria',
            image: '/images/uslugi_pediatry.png',
            description: 'Opieka nad dziećmi',
            href: '/uslugi#pediatria'
        },
        {
            name: 'Laboratorium',
            image: '/images/laboratorium.png',
            description: 'Pełne badania krwi',
            href: '/uslugi'
        },
        {
            name: 'Profilaktyka',
            image: '/images/profilaktyka_uslugi.png',
            description: 'Badania profilaktyczne',
            href: '/uslugi'
        },
    ];

    const features = [
        {
            image: '/images/inonka_sluchawki_lekarskie.png',
            title: 'Doświadczenie',
            description: 'Ponad 8 lat na rynku i 15 000 zadowolonych pacjentów rocznie.'
        },
        {
            image: '/images/profilaktycznebadania.png',
            title: 'Nowoczesność',
            description: 'Najnowszy sprzęt diagnostyczny i komfortowe gabinety.'
        },
        {
            image: '/images/szczepienie_uslugi.png',
            title: 'Wygoda',
            description: 'Rezerwacja online 24/7 i przypomnienia SMS o wizytach.'
        },
    ];

    // Przykładowe aktualności (w przyszłości z API)
    const news = [
        {
            id: 1,
            title: 'Nowy specjalista kardiolog w naszym zespole',
            excerpt: 'Z przyjemnością informujemy, że do naszego zespołu dołączył dr hab. Piotr Zieliński, specjalista kardiolog z 18-letnim doświadczeniem.',
            date: '20.12.2024',
            category: 'Aktualności',
            image: '/images/2149335672.jpg',
        },
        {
            id: 2,
            title: 'Szczepienia przeciw grypie - sezon 2024/2025',
            excerpt: 'Zapraszamy na szczepienia przeciw grypie. Dostępne są szczepionki dla dorosłych i dzieci. Rezerwacja online lub telefonicznie.',
            date: '15.12.2024',
            category: 'Profilaktyka',
            image: '/images/szczepienie_uslugi.png',
        },
        {
            id: 3,
            title: 'Nowe godziny otwarcia w okresie świątecznym',
            excerpt: 'Informujemy o zmienionych godzinach pracy naszych placówek w okresie świąteczno-noworocznym.',
            date: '10.12.2024',
            category: 'Ogłoszenia',
            image: '/images/profilaktycznebadania.png',
        },
    ];

    const locations = [
        {
            name: 'MedVita Centrum',
            address: 'ul. Zdrowa 15, 00-001 Warszawa',
            phone: '+48 22 123 45 67',
        },
        {
            name: 'MedVita Mokotów',
            address: 'ul. Puławska 200, 02-670 Warszawa',
            phone: '+48 22 234 56 78',
        },
        {
            name: 'MedVita Ursynów',
            address: 'ul. Wąwozowa 18, 02-796 Warszawa',
            phone: '+48 22 345 67 89',
        },
    ];
</script>

<svelte:head>
    <title>Strona Główna - MedVita Centrum Zdrowia</title>
    <meta name="description" content="MedVita - Centrum Zdrowia. Kompleksowa opieka medyczna w Warszawie." />
</svelte:head>

<MainLayout>
    <!-- Hero Section -->
    <section class="relative bg-medical-600 text-white overflow-hidden min-h-[600px] lg:min-h-[700px]">
        <picture>
            <source
                type="image/webp"
                srcset="/images/optimized/beautiful-female-therapist-clinic-mobile.webp 480w,
                        /images/optimized/beautiful-female-therapist-clinic-tablet.webp 768w,
                        /images/optimized/beautiful-female-therapist-clinic-desktop.webp 1200w,
                        /images/optimized/beautiful-female-therapist-clinic.webp 1920w"
                sizes="100vw"
            />
            <img
                src="/images/beautiful-female-therapist-clinic.jpg"
                alt=""
                class="absolute inset-0 w-full h-full object-cover"
                fetchpriority="high"
                decoding="async"
            />
        </picture>
        <div class="absolute inset-0 bg-gradient-to-r from-medical-800/95 via-medical-700/85 to-medical-600/70"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                        Kompleksowa opieka medyczna w Warszawie
                    </h1>
                    <p class="text-xl md:text-2xl text-medical-100 mb-8">
                        3 placówki | 12 specjalistów | Rezerwacja online 24/7
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a
                            href="/rezerwacja"
                            class="px-8 py-4 bg-white text-medical-600 font-semibold rounded-lg
                                   hover:bg-medical-50 transition-colors duration-200 text-center
                                   focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
                        >
                            Umów wizytę online
                        </a>
                        <a
                            href="/lekarze"
                            class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg
                                   hover:bg-white/10 transition-colors duration-200 text-center
                                   focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
                        >
                            Znajdź lekarza
                        </a>
                    </div>
                </div>
                <div class="hidden lg:flex justify-end">
                    <picture>
                        <source
                            type="image/webp"
                            srcset="/images/optimized/PIELENGNIARKA-DOBRON_BEZ_TLA_3_max.webp"
                        />
                        <img
                            src="/images/PIELENGNIARKA-DOBRON_BEZ_TLA_3_max.png"
                            alt="Pielęgniarka MedVita"
                            class="max-h-[500px] w-auto drop-shadow-2xl"
                            width="400"
                            height="500"
                            loading="eager"
                            decoding="async"
                        />
                    </picture>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {#each services as service}
                    <a
                        href={service.href}
                        class="bg-gray-50 rounded-xl p-4 sm:p-6 hover:shadow-lg
                               transition-all duration-200 block hover:-translate-y-1
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-medical-500
                               focus-visible:ring-offset-2
                               flex flex-row sm:flex-col items-center gap-4 sm:gap-0"
                        aria-label="{service.name} - {service.description}"
                    >
                        <!-- Mobile: tekst po lewej -->
                        <div class="flex-1 text-left sm:text-center sm:order-2">
                            <h3 class="font-semibold text-gray-900 mb-1 text-base sm:text-lg">{service.name}</h3>
                            <p class="text-sm text-gray-600">{service.description}</p>
                        </div>
                        <!-- Mobile: obrazek po prawej, Desktop: obrazek na górze -->
                        <div class="w-24 h-24 sm:w-40 sm:h-40 lg:w-48 lg:h-48 flex-shrink-0 sm:mx-auto sm:mb-4 sm:order-1" style="aspect-ratio: 1/1;">
                            <picture>
                                <source
                                    type="image/webp"
                                    srcset={service.image.replace(/\.(png|jpg)$/, '.webp').replace('/images/', '/images/optimized/')}
                                />
                                <img
                                    src={service.image}
                                    alt=""
                                    aria-hidden="true"
                                    class="w-full h-full object-contain"
                                    loading="lazy"
                                    decoding="async"
                                    width="192"
                                    height="192"
                                />
                            </picture>
                        </div>
                    </a>
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

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                {#each features as feature}
                    <div class="bg-white rounded-xl p-6 sm:p-8 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 mb-3 sm:mb-4" style="aspect-ratio: 1/1;">
                            <picture>
                                <source
                                    type="image/webp"
                                    srcset={feature.image.replace(/\.(png|jpg)$/, '.webp').replace('/images/', '/images/optimized/')}
                                />
                                <img
                                    src={feature.image}
                                    alt=""
                                    aria-hidden="true"
                                    class="w-full h-full object-contain"
                                    loading="lazy"
                                    decoding="async"
                                    width="64"
                                    height="64"
                                />
                            </picture>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">{feature.title}</h3>
                        <p class="text-sm sm:text-base text-gray-600">{feature.description}</p>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- News / Aktualności -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Aktualności
                    </h2>
                    <p class="text-lg text-gray-600">
                        Bądź na bieżąco z wydarzeniami w MedVita
                    </p>
                </div>
                <a
                    href="/blog"
                    class="hidden sm:inline-flex items-center gap-2 text-medical-600 font-medium hover:text-medical-700"
                >
                    Wszystkie wpisy
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {#each news as article}
                    <article class="bg-gray-50 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow group">
                        <div class="relative h-48 overflow-hidden" style="aspect-ratio: 16/9;">
                            <picture>
                                <source
                                    type="image/webp"
                                    srcset={article.image.replace(/\.(png|jpg)$/, '.webp').replace('/images/', '/images/optimized/')}
                                />
                                <img
                                    src={article.image}
                                    alt={article.title}
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                    decoding="async"
                                    width="400"
                                    height="225"
                                />
                            </picture>
                            <span class="absolute top-4 left-4 px-3 py-1 bg-medical-600 text-white text-xs font-medium rounded-full">
                                {article.category}
                            </span>
                        </div>
                        <div class="p-6">
                            <time class="text-sm text-gray-500">{article.date}</time>
                            <h3 class="text-lg font-semibold text-gray-900 mt-2 mb-3 line-clamp-2">
                                {article.title}
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3">
                                {article.excerpt}
                            </p>
                            <a
                                href="/blog/{article.id}"
                                class="inline-flex items-center gap-1 mt-4 text-medical-600 font-medium text-sm hover:text-medical-700"
                            >
                                Czytaj więcej
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                {/each}
            </div>

            <div class="mt-8 text-center sm:hidden">
                <a
                    href="/blog"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-medical-600 text-white font-medium rounded-lg hover:bg-medical-700"
                >
                    Wszystkie wpisy
                </a>
            </div>
        </div>
    </section>

    <!-- Locations Map -->
    <section class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Nasze placówki
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    3 lokalizacje w Warszawie - wybierz najbliższą
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 mb-12">
                {#each locations as location}
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-semibold text-medical-600 mb-4">
                            {location.name}
                        </h3>
                        <div class="space-y-3 text-gray-600">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-medical-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{location.address}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-medical-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{location.phone.replace(/\s/g, '')}" class="hover:text-medical-600 transition-colors">
                                    {location.phone}
                                </a>
                            </div>
                        </div>
                        <a
                            href="/kontakt"
                            class="inline-block mt-4 text-medical-600 font-medium text-sm hover:text-medical-700"
                        >
                            Zobacz szczegóły
                        </a>
                    </div>
                {/each}
            </div>

            <!-- Map -->
            <div class="rounded-2xl overflow-hidden shadow-lg h-96">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d156388.35438500787!2d20.921111!3d52.233333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ecc669a869f01%3A0x72f0be2a88ead3fc!2sWarszawa!5e0!3m2!1spl!2spl!4v1234567890"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Mapa lokalizacji MedVita w Warszawie"
                ></iframe>
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
                <a
                    href="/rezerwacja"
                    class="px-8 py-4 bg-white text-medical-600 font-semibold rounded-lg
                           hover:bg-medical-50 transition-colors duration-200 text-center
                           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
                >
                    Umów wizytę
                </a>
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
</MainLayout>
