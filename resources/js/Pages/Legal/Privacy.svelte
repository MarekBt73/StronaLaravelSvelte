<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';
</script>

<svelte:head>
    <title>Polityka prywatności - MedVita Centrum Zdrowia</title>
    <meta name="description" content="Polityka prywatności MedVita. Dowiedz się, jak chronimy Twoje dane osobowe zgodnie z RODO." />
</svelte:head>

<MainLayout>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold">
                Polityka prywatności
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
                <h2>1. Administrator danych osobowych</h2>
                <p>
                    Administratorem Twoich danych osobowych jest MedVita Centrum Zdrowia sp. z o.o. z siedzibą w Warszawie (00-001), ul. Zdrowa 15, NIP: 1234567890, REGON: 987654321.
                </p>
                <p>
                    Kontakt z Inspektorem Ochrony Danych: iod@medvita.pl
                </p>

                <h2>2. Jakie dane zbieramy</h2>
                <p>W zależności od sposobu korzystania z naszych usług, możemy zbierać następujące dane:</p>

                <h3>2.1. Dane podawane przez Ciebie</h3>
                <ul>
                    <li>Imię i nazwisko</li>
                    <li>Adres e-mail</li>
                    <li>Numer telefonu</li>
                    <li>PESEL (w przypadku wizyt medycznych)</li>
                    <li>Adres zamieszkania</li>
                    <li>Dane dotyczące zdrowia (w ramach dokumentacji medycznej)</li>
                </ul>

                <h3>2.2. Dane zbierane automatycznie</h3>
                <ul>
                    <li>Adres IP</li>
                    <li>Typ przeglądarki i urządzenia</li>
                    <li>Dane o sposobie korzystania ze strony (pliki cookies)</li>
                    <li>Lokalizacja (za Twoją zgodą)</li>
                </ul>

                <h2>3. Cele przetwarzania danych</h2>
                <p>Twoje dane przetwarzamy w następujących celach:</p>
                <ul>
                    <li><strong>Świadczenie usług medycznych</strong> - prowadzenie dokumentacji medycznej, realizacja wizyt (podstawa: art. 6 ust. 1 lit. b i c RODO, art. 9 ust. 2 lit. h RODO)</li>
                    <li><strong>Rezerwacja wizyt</strong> - obsługa systemu rezerwacji online (podstawa: art. 6 ust. 1 lit. b RODO)</li>
                    <li><strong>Kontakt</strong> - odpowiadanie na pytania i zgłoszenia (podstawa: art. 6 ust. 1 lit. f RODO)</li>
                    <li><strong>Marketing</strong> - wysyłanie informacji o usługach, za Twoją zgodą (podstawa: art. 6 ust. 1 lit. a RODO)</li>
                    <li><strong>Analityka</strong> - ulepszanie naszych usług i strony internetowej (podstawa: art. 6 ust. 1 lit. f RODO)</li>
                </ul>

                <h2>4. Odbiorcy danych</h2>
                <p>Twoje dane mogą być przekazywane następującym podmiotom:</p>
                <ul>
                    <li>Personelowi medycznemu MedVita</li>
                    <li>Podmiotom przetwarzającym dane w naszym imieniu (hosting, systemy IT)</li>
                    <li>Laboratoriom diagnostycznym (w przypadku zlecenia badań)</li>
                    <li>Ubezpieczycielom (za Twoją zgodą)</li>
                    <li>Organom publicznym (gdy wymaga tego prawo)</li>
                </ul>

                <h2>5. Okres przechowywania danych</h2>
                <ul>
                    <li><strong>Dokumentacja medyczna</strong> - 20 lat od końca roku kalendarzowego, w którym dokonano ostatniego wpisu</li>
                    <li><strong>Dane kontaktowe (newsletter)</strong> - do czasu wycofania zgody</li>
                    <li><strong>Dane z formularza kontaktowego</strong> - 2 lata</li>
                    <li><strong>Dane analityczne (cookies)</strong> - zgodnie z polityką cookies</li>
                </ul>

                <h2>6. Twoje prawa</h2>
                <p>Przysługują Ci następujące prawa:</p>
                <ul>
                    <li><strong>Prawo dostępu</strong> - możesz uzyskać informacje o przetwarzanych danych</li>
                    <li><strong>Prawo do sprostowania</strong> - możesz poprawić nieprawidłowe dane</li>
                    <li><strong>Prawo do usunięcia</strong> - możesz żądać usunięcia danych (z wyjątkiem dokumentacji medycznej)</li>
                    <li><strong>Prawo do ograniczenia przetwarzania</strong></li>
                    <li><strong>Prawo do przenoszenia danych</strong></li>
                    <li><strong>Prawo do sprzeciwu</strong> - wobec przetwarzania w celach marketingowych</li>
                    <li><strong>Prawo do wycofania zgody</strong> - w dowolnym momencie</li>
                </ul>
                <p>
                    Aby skorzystać z powyższych praw, skontaktuj się z nami: iod@medvita.pl
                </p>

                <h2>7. Prawo do skargi</h2>
                <p>
                    Jeśli uważasz, że przetwarzanie Twoich danych narusza przepisy RODO, masz prawo wnieść skargę do Prezesa Urzędu Ochrony Danych Osobowych (ul. Stawki 2, 00-193 Warszawa).
                </p>

                <h2>8. Bezpieczeństwo danych</h2>
                <p>
                    Stosujemy odpowiednie środki techniczne i organizacyjne w celu ochrony Twoich danych osobowych, w tym:
                </p>
                <ul>
                    <li>Szyfrowanie SSL/TLS</li>
                    <li>Kontrolę dostępu do systemów</li>
                    <li>Regularne szkolenia personelu</li>
                    <li>Procedury backup i disaster recovery</li>
                </ul>

                <h2>9. Pliki cookies</h2>
                <p>
                    Nasza strona wykorzystuje pliki cookies. Szczegółowe informacje znajdziesz w naszej <a href="/polityka-cookies" class="text-medical-600 hover:underline">Polityce cookies</a>.
                </p>

                <h2>10. Zmiany polityki prywatności</h2>
                <p>
                    Możemy aktualizować niniejszą Politykę prywatności. O istotnych zmianach poinformujemy Cię poprzez publikację na stronie lub bezpośrednią wiadomość.
                </p>

                <h2>11. Kontakt</h2>
                <p>
                    W przypadku pytań dotyczących ochrony danych osobowych:
                </p>
                <ul>
                    <li>Inspektor Ochrony Danych: iod@medvita.pl</li>
                    <li>Telefon: +48 22 123 45 67</li>
                    <li>Adres: ul. Zdrowa 15, 00-001 Warszawa</li>
                </ul>
            </div>
        </div>
    </section>
</MainLayout>
