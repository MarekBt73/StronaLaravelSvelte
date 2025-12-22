<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';
</script>

<svelte:head>
    <title>RODO - MedVita Centrum Zdrowia</title>
    <meta name="description" content="Informacje o przetwarzaniu danych osobowych w MedVita zgodnie z RODO. Twoje prawa jako pacjenta." />
</svelte:head>

<MainLayout>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold">
                RODO - Ochrona Danych Osobowych
            </h1>
            <p class="text-medical-100 mt-2">
                Informacje o przetwarzaniu danych osobowych pacjentów
            </p>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12 lg:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                <div class="bg-medical-50 border-l-4 border-medical-600 p-6 rounded-r-lg mb-8">
                    <h2 class="text-medical-800 mt-0">Klauzula informacyjna RODO</h2>
                    <p class="text-medical-700 mb-0">
                        Zgodnie z art. 13 ust. 1 i 2 Rozporządzenia Parlamentu Europejskiego i Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. (RODO) informujemy, że:
                    </p>
                </div>

                <h2>1. Administrator danych</h2>
                <p>
                    Administratorem Państwa danych osobowych jest:
                </p>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="font-semibold mb-1">MedVita Centrum Zdrowia sp. z o.o.</p>
                    <p class="text-gray-600 mb-1">ul. Zdrowa 15, 00-001 Warszawa</p>
                    <p class="text-gray-600 mb-1">NIP: 1234567890, REGON: 987654321</p>
                    <p class="text-gray-600">KRS: 0000123456</p>
                </div>

                <h2>2. Inspektor Ochrony Danych</h2>
                <p>
                    Administrator wyznaczył Inspektora Ochrony Danych, z którym można się kontaktować:
                </p>
                <ul>
                    <li>Email: <a href="mailto:iod@medvita.pl" class="text-medical-600">iod@medvita.pl</a></li>
                    <li>Listownie: IOD MedVita, ul. Zdrowa 15, 00-001 Warszawa</li>
                </ul>

                <h2>3. Cel i podstawa prawna przetwarzania</h2>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Cel przetwarzania</th>
                            <th>Podstawa prawna</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Udzielanie świadczeń zdrowotnych</td>
                            <td>Art. 9 ust. 2 lit. h RODO w zw. z ustawą o prawach pacjenta</td>
                        </tr>
                        <tr>
                            <td>Prowadzenie dokumentacji medycznej</td>
                            <td>Art. 6 ust. 1 lit. c RODO (obowiązek prawny)</td>
                        </tr>
                        <tr>
                            <td>Rezerwacja i obsługa wizyt</td>
                            <td>Art. 6 ust. 1 lit. b RODO (umowa)</td>
                        </tr>
                        <tr>
                            <td>Rozliczenia finansowe</td>
                            <td>Art. 6 ust. 1 lit. c RODO (obowiązek prawny)</td>
                        </tr>
                        <tr>
                            <td>Marketing (za zgodą)</td>
                            <td>Art. 6 ust. 1 lit. a RODO (zgoda)</td>
                        </tr>
                        <tr>
                            <td>Dochodzenie roszczeń</td>
                            <td>Art. 6 ust. 1 lit. f RODO (prawnie uzasadniony interes)</td>
                        </tr>
                    </tbody>
                </table>

                <h2>4. Kategorie przetwarzanych danych</h2>
                <h3>Dane zwykłe:</h3>
                <ul>
                    <li>Dane identyfikacyjne (imię, nazwisko, PESEL)</li>
                    <li>Dane kontaktowe (adres, telefon, e-mail)</li>
                    <li>Dane adresowe</li>
                </ul>
                <h3>Dane szczególnej kategorii (wrażliwe):</h3>
                <ul>
                    <li>Dane dotyczące zdrowia</li>
                    <li>Historia leczenia</li>
                    <li>Wyniki badań</li>
                </ul>

                <h2>5. Odbiorcy danych</h2>
                <p>Dane mogą być przekazywane:</p>
                <ul>
                    <li>Personelowi medycznemu udzielającemu świadczeń</li>
                    <li>Podmiotom współpracującym (laboratoria, podwykonawcy)</li>
                    <li>Dostawcom usług IT i hostingu</li>
                    <li>Ubezpieczycielom (za zgodą pacjenta)</li>
                    <li>Organom publicznym (NFZ, sanepid, policja - na podstawie przepisów prawa)</li>
                </ul>

                <h2>6. Okres przechowywania danych</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="mb-0">
                        <li><strong>Dokumentacja medyczna:</strong> 20 lat od końca roku kalendarzowego, w którym dokonano ostatniego wpisu (lub dłużej w przypadkach określonych ustawą)</li>
                        <li><strong>Dokumentacja medyczna dzieci do 2. roku życia:</strong> 22 lata</li>
                        <li><strong>Skierowania:</strong> 5 lat od realizacji lub końca roku wystawienia (jeśli niezrealizowane)</li>
                        <li><strong>Dane do celów podatkowych:</strong> 5 lat</li>
                        <li><strong>Dane marketingowe:</strong> do wycofania zgody</li>
                    </ul>
                </div>

                <h2>7. Prawa pacjenta związane z danymi osobowymi</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-medical-700 mb-2">Prawo dostępu</h4>
                        <p class="text-sm text-gray-600 mb-0">Możesz uzyskać informację, jakie dane przetwarzamy i w jakim celu.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-medical-700 mb-2">Prawo do sprostowania</h4>
                        <p class="text-sm text-gray-600 mb-0">Możesz poprawić nieprawidłowe lub niekompletne dane.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-medical-700 mb-2">Prawo do usunięcia*</h4>
                        <p class="text-sm text-gray-600 mb-0">W określonych przypadkach możesz żądać usunięcia danych.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-medical-700 mb-2">Prawo do ograniczenia</h4>
                        <p class="text-sm text-gray-600 mb-0">Możesz żądać ograniczenia przetwarzania danych.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-medical-700 mb-2">Prawo do przenoszenia</h4>
                        <p class="text-sm text-gray-600 mb-0">Możesz otrzymać dane w formacie umożliwiającym przeniesienie.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-medical-700 mb-2">Prawo do sprzeciwu</h4>
                        <p class="text-sm text-gray-600 mb-0">Możesz sprzeciwić się przetwarzaniu (np. marketing).</p>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    * Uwaga: Prawo do usunięcia danych nie dotyczy dokumentacji medycznej, która musi być przechowywana przez okres określony przepisami prawa.
                </p>

                <h2>8. Prawo do skargi</h2>
                <p>
                    Jeśli uważasz, że przetwarzanie Twoich danych osobowych narusza przepisy RODO, masz prawo wnieść skargę do organu nadzorczego:
                </p>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="font-semibold mb-1">Prezes Urzędu Ochrony Danych Osobowych</p>
                    <p class="text-gray-600 mb-1">ul. Stawki 2, 00-193 Warszawa</p>
                    <p class="text-gray-600">www.uodo.gov.pl</p>
                </div>

                <h2>9. Informacja o wymogu podania danych</h2>
                <p>
                    Podanie danych osobowych jest:
                </p>
                <ul>
                    <li><strong>Obowiązkowe</strong> - w zakresie niezbędnym do udzielenia świadczeń zdrowotnych i prowadzenia dokumentacji medycznej (wynika z przepisów prawa)</li>
                    <li><strong>Dobrowolne</strong> - w zakresie danych kontaktowych służących do przypominania o wizytach i celów marketingowych</li>
                </ul>

                <h2>10. Zautomatyzowane podejmowanie decyzji</h2>
                <p>
                    Nie podejmujemy decyzji opartych wyłącznie na zautomatyzowanym przetwarzaniu danych, w tym profilowaniu, które wywołują skutki prawne lub istotnie wpływają na pacjenta.
                </p>

                <h2>11. Przekazywanie danych poza EOG</h2>
                <p>
                    Dane osobowe nie są przekazywane do państw trzecich (poza Europejski Obszar Gospodarczy) ani organizacji międzynarodowych.
                </p>

                <h2>Kontakt w sprawach RODO</h2>
                <div class="bg-medical-50 p-6 rounded-lg">
                    <p class="font-semibold text-medical-800 mb-4">Inspektor Ochrony Danych - MedVita</p>
                    <ul class="mb-0">
                        <li>Email: <a href="mailto:iod@medvita.pl" class="text-medical-600 font-medium">iod@medvita.pl</a></li>
                        <li>Telefon: +48 22 123 45 67 (wew. 5)</li>
                        <li>Adres: ul. Zdrowa 15, 00-001 Warszawa</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</MainLayout>
