<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';
</script>

<svelte:head>
    <title>Regulamin - MedVita Centrum Zdrowia</title>
    <meta name="description" content="Regulamin korzystania z usług MedVita Centrum Zdrowia. Zasady rezerwacji wizyt, płatności i korzystania z serwisu." />
</svelte:head>

<MainLayout>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold">
                Regulamin
            </h1>
            <p class="text-medical-100 mt-2">
                Ostatnia aktualizacja: 1 stycznia 2024
            </p>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12 lg:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                <h2>1. Postanowienia ogólne</h2>
                <p>
                    Niniejszy Regulamin określa zasady korzystania z usług medycznych świadczonych przez MedVita Centrum Zdrowia sp. z o.o. z siedzibą w Warszawie (00-001), ul. Zdrowa 15, NIP: 1234567890, REGON: 987654321 (dalej: "MedVita" lub "Przychodnia").
                </p>
                <p>
                    Korzystanie z usług MedVita oznacza akceptację niniejszego Regulaminu.
                </p>

                <h2>2. Definicje</h2>
                <ul>
                    <li><strong>Pacjent</strong> - osoba fizyczna korzystająca z usług medycznych MedVita.</li>
                    <li><strong>Usługa medyczna</strong> - świadczenie zdrowotne realizowane przez personel medyczny MedVita.</li>
                    <li><strong>Wizyta</strong> - konsultacja lekarska lub badanie diagnostyczne.</li>
                    <li><strong>System rezerwacji</strong> - elektroniczny system umożliwiający rezerwację wizyt online.</li>
                </ul>

                <h2>3. Rezerwacja wizyt</h2>
                <h3>3.1. Sposoby rezerwacji</h3>
                <p>Rezerwacji wizyty można dokonać:</p>
                <ul>
                    <li>Online poprzez system rezerwacji na stronie medvita.pl</li>
                    <li>Telefonicznie pod numerem: +48 22 123 45 67</li>
                    <li>Osobiście w recepcji dowolnej placówki MedVita</li>
                </ul>

                <h3>3.2. Potwierdzenie rezerwacji</h3>
                <p>
                    Po dokonaniu rezerwacji Pacjent otrzyma potwierdzenie na podany adres e-mail oraz SMS na podany numer telefonu. Potwierdzenie zawiera datę, godzinę, miejsce wizyty oraz imię i nazwisko lekarza.
                </p>

                <h3>3.3. Odwoływanie wizyt</h3>
                <p>
                    Wizytę można odwołać bezpłatnie najpóźniej na 24 godziny przed jej planowanym terminem. W przypadku odwołania wizyty w terminie krótszym lub niestawienia się na wizytę bez uprzedniego odwołania, MedVita zastrzega sobie prawo do pobrania opłaty w wysokości 50% ceny wizyty.
                </p>

                <h2>4. Płatności</h2>
                <h3>4.1. Formy płatności</h3>
                <p>MedVita akceptuje następujące formy płatności:</p>
                <ul>
                    <li>Gotówka</li>
                    <li>Karty płatnicze (Visa, Mastercard)</li>
                    <li>Płatności online (BLIK, przelewy)</li>
                    <li>Ubezpieczenia prywatne (po wcześniejszej weryfikacji)</li>
                </ul>

                <h3>4.2. Cennik</h3>
                <p>
                    Aktualny cennik usług dostępny jest na stronie internetowej oraz w recepcji każdej placówki. Ceny mogą ulec zmianie, przy czym zmiana cen nie dotyczy wizyt już zarezerwowanych.
                </p>

                <h2>5. Obowiązki Pacjenta</h2>
                <p>Pacjent zobowiązany jest do:</p>
                <ul>
                    <li>Podania prawdziwych danych osobowych i kontaktowych</li>
                    <li>Przedstawienia dokumentu tożsamości przed wizytą</li>
                    <li>Punktualnego stawienia się na wizytę</li>
                    <li>Poinformowania o przyjmowanych lekach i chorobach współistniejących</li>
                    <li>Stosowania się do zaleceń personelu medycznego</li>
                </ul>

                <h2>6. Obowiązki MedVita</h2>
                <p>MedVita zobowiązuje się do:</p>
                <ul>
                    <li>Świadczenia usług medycznych zgodnie z aktualną wiedzą medyczną</li>
                    <li>Zapewnienia wykwalifikowanego personelu medycznego</li>
                    <li>Ochrony danych osobowych Pacjentów zgodnie z RODO</li>
                    <li>Prowadzenia dokumentacji medycznej zgodnie z obowiązującymi przepisami</li>
                    <li>Informowania o zmianach w terminach wizyt</li>
                </ul>

                <h2>7. Reklamacje</h2>
                <p>
                    Reklamacje dotyczące świadczonych usług można składać pisemnie, mailowo na adres reklamacje@medvita.pl lub osobiście w recepcji. Reklamacja zostanie rozpatrzona w terminie 14 dni roboczych od daty jej otrzymania.
                </p>

                <h2>8. Odpowiedzialność</h2>
                <p>
                    MedVita ponosi odpowiedzialność za szkody wyrządzone Pacjentom w związku ze świadczeniem usług medycznych na zasadach określonych w obowiązujących przepisach prawa. MedVita posiada ubezpieczenie od odpowiedzialności cywilnej.
                </p>

                <h2>9. Postanowienia końcowe</h2>
                <p>
                    MedVita zastrzega sobie prawo do zmiany Regulaminu. O zmianach Pacjenci zostaną poinformowani poprzez publikację nowego Regulaminu na stronie internetowej. W sprawach nieuregulowanych niniejszym Regulaminem zastosowanie mają przepisy Kodeksu cywilnego oraz ustawy o prawach pacjenta i Rzeczniku Praw Pacjenta.
                </p>

                <h2>10. Kontakt</h2>
                <p>
                    W przypadku pytań dotyczących Regulaminu prosimy o kontakt:
                </p>
                <ul>
                    <li>Email: kontakt@medvita.pl</li>
                    <li>Telefon: +48 22 123 45 67</li>
                    <li>Adres: ul. Zdrowa 15, 00-001 Warszawa</li>
                </ul>
            </div>
        </div>
    </section>
</MainLayout>
