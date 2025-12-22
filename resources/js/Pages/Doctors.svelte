<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';

    const doctors = [
        {
            name: 'dr n. med. Anna Kowalska',
            specialty: 'Internista, Kardiolog',
            role: 'Dyrektor Medyczny',
            experience: '15 lat doświadczenia',
            education: 'Warszawski Uniwersytet Medyczny',
            description: 'Specjalizuje się w chorobach układu krążenia i nadciśnieniu tętniczym. Prowadzi pacjentów z niewydolnością serca.',
            languages: ['Polski', 'Angielski'],
            locations: ['MedVita Centrum'],
            image: '/images/polozna.png',
        },
        {
            name: 'dr Tomasz Nowak',
            specialty: 'Pediatra',
            role: 'Specjalista',
            experience: '12 lat doświadczenia',
            education: 'Gdański Uniwersytet Medyczny',
            description: 'Ekspert w zakresie pediatrii ogólnej i alergologii dziecięcej. Szczególnie lubiony przez młodych pacjentów.',
            languages: ['Polski', 'Angielski', 'Niemiecki'],
            locations: ['MedVita Mokotów', 'MedVita Ursynów'],
            image: '/images/2148998309.jpg',
        },
        {
            name: 'dr Maria Wiśniewska',
            specialty: 'Dermatolog',
            role: 'Specjalista',
            experience: '10 lat doświadczenia',
            education: 'Collegium Medicum UJ',
            description: 'Specjalizuje się w dermatologii estetycznej i leczeniu chorób skóry. Wykonuje zabiegi z zakresu dermatochirurgii.',
            languages: ['Polski', 'Angielski'],
            locations: ['MedVita Centrum', 'MedVita Mokotów'],
            image: '/images/piekna-terapeutka-w-klinice.png',
        },
        {
            name: 'dr hab. Piotr Zieliński',
            specialty: 'Ortopeda',
            role: 'Specjalista',
            experience: '18 lat doświadczenia',
            education: 'Śląski Uniwersytet Medyczny',
            description: 'Uznany specjalista w zakresie chirurgii kolana i barku. Przeprowadził ponad 2000 artroskopii.',
            languages: ['Polski', 'Angielski'],
            locations: ['MedVita Centrum'],
            image: '/images/2149335672.jpg',
        },
        {
            name: 'dr Ewa Kamińska',
            specialty: 'Ginekolog',
            role: 'Specjalista',
            experience: '14 lat doświadczenia',
            education: 'Warszawski Uniwersytet Medyczny',
            description: 'Prowadzi ciąże wysokiego ryzyka i specjalizuje się w endokrynologii ginekologicznej.',
            languages: ['Polski', 'Angielski', 'Rosyjski'],
            locations: ['MedVita Mokotów', 'MedVita Ursynów'],
            image: '/images/blond_Pielengniarka.webp',
        },
        {
            name: 'dr Jan Lewandowski',
            specialty: 'Neurolog',
            role: 'Specjalista',
            experience: '11 lat doświadczenia',
            education: 'Uniwersytet Medyczny w Łodzi',
            description: 'Specjalizuje się w leczeniu bólów głowy, migreny oraz chorób neurodegeneracyjnych.',
            languages: ['Polski', 'Angielski'],
            locations: ['MedVita Centrum', 'MedVita Ursynów'],
            image: '/images/2148239052.jpg',
        },
    ];
</script>

<svelte:head>
    <title>Nasi lekarze - MedVita Centrum Zdrowia</title>
    <meta name="description" content="Poznaj zespół specjalistów MedVita. Doświadczeni lekarze: kardiolog, pediatra, dermatolog, ortopeda, ginekolog, neurolog." />
</svelte:head>

<MainLayout>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    Nasi lekarze
                </h1>
                <p class="text-xl text-medical-100">
                    Zespół doświadczonych specjalistów, którzy każdego dnia dbają
                    o zdrowie naszych pacjentów z najwyższą starannością.
                </p>
            </div>
        </div>
    </section>

    <!-- Doctors Grid -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {#each doctors as doctor}
                    <article class="bg-gray-50 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Photo -->
                        <div class="relative h-64 bg-medical-100">
                            <img
                                src={doctor.image}
                                alt={doctor.name}
                                class="w-full h-full object-cover object-top"
                            />
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-medical-900/90 to-transparent p-4">
                                <h2 class="font-bold text-lg text-white">{doctor.name}</h2>
                                <p class="text-medical-200 text-sm">{doctor.specialty}</p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-3 py-1 bg-medical-100 text-medical-700 text-xs font-medium rounded-full">
                                    {doctor.role}
                                </span>
                                <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-medium rounded-full">
                                    {doctor.experience}
                                </span>
                            </div>

                            <p class="text-gray-600 text-sm mb-4">
                                {doctor.description}
                            </p>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-start gap-2">
                                    <span class="text-gray-400">🎓</span>
                                    <span class="text-gray-600">{doctor.education}</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-gray-400">🌍</span>
                                    <span class="text-gray-600">{doctor.languages.join(', ')}</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-gray-400">📍</span>
                                    <span class="text-gray-600">{doctor.locations.join(', ')}</span>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <a
                                    href="/rezerwacja"
                                    class="block w-full text-center px-4 py-2 bg-medical-600 text-white font-medium rounded-lg
                                           hover:bg-medical-700 transition-colors"
                                >
                                    Umów wizytę
                                </a>
                            </div>
                        </div>
                    </article>
                {/each}
            </div>
        </div>
    </section>

    <!-- Join Team CTA -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                Dołącz do naszego zespołu
            </h2>
            <p class="text-gray-600 mb-8">
                Szukasz miejsca, gdzie możesz rozwijać swoją karierę medyczną?
                Zapraszamy do kontaktu.
            </p>
            <a
                href="/kontakt"
                class="inline-block px-6 py-3 border-2 border-medical-600 text-medical-600 font-semibold rounded-lg
                       hover:bg-medical-600 hover:text-white transition-colors"
            >
                Wyślij CV
            </a>
        </div>
    </section>
</MainLayout>
