<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';

    const services = [
        {
            category: 'Podstawowa opieka zdrowotna',
            image: '/images/lekarz_rodzinny.png',
            items: [
                { name: 'Konsultacja internistyczna', price: 'od 180 zł', duration: '30 min' },
                { name: 'Badania laboratoryjne', price: 'od 50 zł', duration: '15 min' },
                { name: 'EKG z opisem', price: '100 zł', duration: '20 min' },
                { name: 'Szczepienia ochronne', price: 'od 80 zł', duration: '15 min' },
            ]
        },
        {
            category: 'Kardiologia',
            image: '/images/inonka_sluchawki_lekarskie.png',
            items: [
                { name: 'Konsultacja kardiologiczna', price: 'od 250 zł', duration: '45 min' },
                { name: 'Echo serca', price: '350 zł', duration: '30 min' },
                { name: 'Holter EKG 24h', price: '250 zł', duration: '24h' },
                { name: 'Holter ciśnieniowy', price: '200 zł', duration: '24h' },
            ]
        },
        {
            category: 'Pediatria',
            image: '/images/uslugi_pediatry.png',
            items: [
                { name: 'Konsultacja pediatryczna', price: 'od 200 zł', duration: '30 min' },
                { name: 'Bilans zdrowia dziecka', price: '250 zł', duration: '45 min' },
                { name: 'Szczepienia dziecięce', price: 'od 100 zł', duration: '20 min' },
                { name: 'Porada żywieniowa dla dzieci', price: '180 zł', duration: '30 min' },
            ]
        },
        {
            category: 'Dermatologia',
            image: '/images/profilaktyka_uslugi.png',
            items: [
                { name: 'Konsultacja dermatologiczna', price: 'od 220 zł', duration: '30 min' },
                { name: 'Dermatoskopia', price: '150 zł', duration: '20 min' },
                { name: 'Usuwanie zmian skórnych', price: 'od 300 zł', duration: '30 min' },
                { name: 'Leczenie trądziku', price: '200 zł', duration: '30 min' },
            ]
        },
        {
            category: 'Ginekologia',
            image: '/images/polozna.png',
            items: [
                { name: 'Konsultacja ginekologiczna', price: 'od 220 zł', duration: '30 min' },
                { name: 'USG ginekologiczne', price: '200 zł', duration: '20 min' },
                { name: 'Cytologia', price: '100 zł', duration: '15 min' },
                { name: 'Prowadzenie ciąży', price: 'pakiet od 2500 zł', duration: '-' },
            ]
        },
        {
            category: 'Stomatologia',
            image: '/images/stopmatolog_uslugi.png',
            items: [
                { name: 'Przegląd stomatologiczny', price: 'od 100 zł', duration: '30 min' },
                { name: 'Leczenie kanałowe', price: 'od 400 zł', duration: '60 min' },
                { name: 'Wybielanie zębów', price: 'od 600 zł', duration: '60 min' },
                { name: 'Protetyka', price: 'od 800 zł', duration: '-' },
            ]
        },
    ];
</script>

<svelte:head>
    <title>Usługi medyczne - MedVita Centrum Zdrowia</title>
    <meta name="description" content="Pełna oferta usług medycznych MedVita: kardiologia, pediatria, dermatologia, ginekologia, ortopedia. Sprawdź cennik i umów wizytę." />
</svelte:head>

<MainLayout>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    Nasze usługi
                </h1>
                <p class="text-xl text-medical-100">
                    Oferujemy kompleksową opiekę medyczną w wielu specjalizacjach.
                    Wszystkie usługi dostępne w 3 lokalizacjach w Warszawie.
                </p>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-16">
                {#each services as service}
                    <div id={service.category.toLowerCase().replace(/\s+/g, '-')}>
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 flex-shrink-0">
                                <img
                                    src={service.image}
                                    alt={service.category}
                                    class="w-full h-full object-contain"
                                />
                            </div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                                {service.category}
                            </h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            {#each service.items as item}
                                <div class="bg-gray-50 rounded-xl p-6 flex justify-between items-center
                                            hover:bg-medical-50 transition-colors">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-1">{item.name}</h3>
                                        <p class="text-sm text-gray-500">Czas trwania: {item.duration}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-medical-600">{item.price}</div>
                                        <a
                                            href="/rezerwacja"
                                            class="text-sm text-medical-600 hover:underline"
                                        >
                                            Umów wizytę
                                        </a>
                                    </div>
                                </div>
                            {/each}
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- Info Banner -->
    <section class="py-12 bg-medical-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">
                            Nie znalazłeś usługi?
                        </h2>
                        <p class="text-gray-600 mb-6">
                            Nasza oferta jest stale rozszerzana. Skontaktuj się z nami,
                            a pomożemy znaleźć odpowiedniego specjalistę.
                        </p>
                        <a
                            href="/kontakt"
                            class="inline-block px-6 py-3 bg-medical-600 text-white font-semibold rounded-lg
                                   hover:bg-medical-700 transition-colors"
                        >
                            Skontaktuj się
                        </a>
                    </div>
                    <div class="text-center">
                        <div class="inline-flex items-center gap-4 bg-medical-100 rounded-xl px-6 py-4">
                            <span class="text-3xl">📞</span>
                            <div class="text-left">
                                <div class="text-sm text-medical-600">Rejestracja telefoniczna</div>
                                <div class="text-xl font-bold text-medical-800">+48 22 123 45 67</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-medical-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Zarezerwuj wizytę online
            </h2>
            <p class="text-xl text-medical-100 mb-8">
                Wybierz specjalistę, datę i godzinę. Wszystko w kilka kliknięć.
            </p>
            <a
                href="/rezerwacja"
                class="inline-block px-8 py-4 bg-white text-medical-600 font-semibold rounded-lg
                       hover:bg-medical-50 transition-colors
                       focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/50"
            >
                Umów wizytę online
            </a>
        </div>
    </section>
</MainLayout>
