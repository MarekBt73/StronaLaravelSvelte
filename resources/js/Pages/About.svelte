<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';

    const stats = [
        { value: '8+', label: 'lat doświadczenia' },
        { value: '15 000+', label: 'pacjentów rocznie' },
        { value: '12', label: 'specjalistów' },
        { value: '3', label: 'placówki' },
    ];

    const values = [
        {
            icon: '❤️',
            title: 'Pacjent w centrum uwagi',
            description: 'Każdy pacjent jest dla nas wyjątkowy. Słuchamy, rozumiemy i działamy w Twoim najlepszym interesie.'
        },
        {
            icon: '🎯',
            title: 'Profesjonalizm',
            description: 'Nasi lekarze to doświadczeni specjaliści, którzy stale podnoszą swoje kwalifikacje.'
        },
        {
            icon: '🔬',
            title: 'Nowoczesność',
            description: 'Inwestujemy w najnowszy sprzęt diagnostyczny, aby zapewnić precyzyjną diagnostykę.'
        },
        {
            icon: '🤝',
            title: 'Zaufanie',
            description: 'Budujemy długotrwałe relacje z pacjentami oparte na wzajemnym szacunku i zaufaniu.'
        },
    ];
</script>

<svelte:head>
    <title>O nas - MedVita Centrum Zdrowia</title>
    <meta name="description" content="Poznaj MedVita - Centrum Zdrowia w Warszawie. 8 lat doświadczenia, 12 specjalistów, 3 placówki. Twoje zdrowie, nasza misja." />
</svelte:head>

<MainLayout>
    <!-- Hero -->
    <section class="relative bg-medical-600 text-white py-16 lg:py-24 overflow-hidden">
        <img
            src="/images/side-view-doctor-checking-patient.jpg"
            alt=""
            class="absolute inset-0 w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-medical-800/95 to-medical-700/80"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    O nas
                </h1>
                <p class="text-xl text-medical-100">
                    MedVita to nowoczesne centrum zdrowia, gdzie doświadczeni specjaliści
                    i zaawansowana diagnostyka spotykają się, by zadbać o Twoje zdrowie.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-12 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                {#each stats as stat}
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-medical-600 mb-2">
                            {stat.value}
                        </div>
                        <div class="text-gray-600">{stat.label}</div>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- Historia -->
    <section class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Nasza historia
                    </h2>
                    <div class="prose prose-lg text-gray-600">
                        <p>
                            MedVita powstała w 2015 roku z pasji do medycyny i chęci stworzenia miejsca,
                            gdzie pacjenci mogą otrzymać kompleksową opiekę zdrowotną na najwyższym poziomie.
                        </p>
                        <p>
                            Zaczynaliśmy od jednej przychodni na Mokotowie. Dziś jesteśmy siecią
                            trzech nowoczesnych placówek w Warszawie, zatrudniającą 12 doświadczonych
                            specjalistów różnych dziedzin medycyny.
                        </p>
                        <p>
                            Naszą misją jest zapewnienie pacjentom dostępu do wysokiej jakości
                            usług medycznych w przyjaznej atmosferze, z wykorzystaniem nowoczesnych
                            technologii diagnostycznych.
                        </p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img
                            src="/images/portrait-nurse-holding-clipboard.jpg"
                            alt="Zespół MedVita"
                            class="w-full h-64 object-cover"
                        />
                    </div>
                    <div class="bg-medical-100 rounded-2xl p-6 lg:p-8">
                        <blockquote class="text-lg italic text-medical-800">
                            "Wierzymy, że każdy pacjent zasługuje na indywidualne podejście
                            i najwyższą jakość opieki medycznej."
                        </blockquote>
                        <div class="mt-4 flex items-center gap-4">
                            <img
                                src="/images/polozna.png"
                                alt="dr n. med. Anna Kowalska"
                                class="w-12 h-12 rounded-full object-cover bg-medical-200"
                            />
                            <div>
                                <div class="font-semibold text-gray-900">dr n. med. Anna Kowalska</div>
                                <div class="text-gray-600 text-sm">Dyrektor Medyczny</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wartości -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Nasze wartości
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Wartości, które kierują naszą codzienną pracą i relacjami z pacjentami.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                {#each values as value}
                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <div class="text-4xl mb-4">{value.icon}</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{value.title}</h3>
                        <p class="text-gray-600 text-sm">{value.description}</p>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-medical-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Zaufaj specjalistom
            </h2>
            <p class="text-xl text-medical-100 mb-8">
                Umów wizytę już dziś i przekonaj się, że Twoje zdrowie jest w dobrych rękach.
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
