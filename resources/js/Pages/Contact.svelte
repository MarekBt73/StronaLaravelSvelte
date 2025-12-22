<script>
    import MainLayout from '@/Components/Layout/MainLayout.svelte';

    const locations = [
        {
            name: 'MedVita Centrum',
            address: 'ul. Zdrowa 15, 00-001 Warszawa',
            phone: '+48 22 123 45 67',
            email: 'centrum@medvita.pl',
            hours: 'Pon-Pt: 8:00-20:00, Sob: 9:00-14:00',
            mapEmbed: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2443.3!2d21.0!3d52.23!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTLCsDEzJzQ4LjAiTiAyMcKwMDAnMDAuMCJF!5e0!3m2!1spl!2spl!4v1234567890',
        },
        {
            name: 'MedVita Mokotów',
            address: 'ul. Puławska 200, 02-670 Warszawa',
            phone: '+48 22 234 56 78',
            email: 'mokotow@medvita.pl',
            hours: 'Pon-Pt: 8:00-20:00, Sob: 9:00-14:00',
            mapEmbed: '',
        },
        {
            name: 'MedVita Ursynów',
            address: 'ul. Wąwozowa 18, 02-796 Warszawa',
            phone: '+48 22 345 67 89',
            email: 'ursynow@medvita.pl',
            hours: 'Pon-Pt: 8:00-20:00, Sob: 9:00-14:00',
            mapEmbed: '',
        },
    ];

    let formData = $state({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
    });

    let isSubmitting = $state(false);
    let submitted = $state(false);

    async function handleSubmit(e) {
        e.preventDefault();
        isSubmitting = true;
        // Symulacja wysłania
        await new Promise(resolve => setTimeout(resolve, 1000));
        isSubmitting = false;
        submitted = true;
    }
</script>

<svelte:head>
    <title>Kontakt - MedVita Centrum Zdrowia</title>
    <meta name="description" content="Skontaktuj się z MedVita. 3 placówki w Warszawie: Centrum, Mokotów, Ursynów. Telefon: +48 22 123 45 67." />
</svelte:head>

<MainLayout>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    Kontakt
                </h1>
                <p class="text-xl text-medical-100">
                    Masz pytania? Chętnie pomożemy. Skontaktuj się z nami telefonicznie,
                    mailowo lub odwiedź jedną z naszych placówek.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Info + Form -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Locations -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Nasze placówki</h2>
                    <div class="space-y-8">
                        {#each locations as location}
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-medical-600 mb-4">
                                    {location.name}
                                </h3>
                                <div class="space-y-3 text-gray-600">
                                    <div class="flex items-start gap-3">
                                        <span class="text-xl">📍</span>
                                        <span>{location.address}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">📞</span>
                                        <a href="tel:{location.phone.replace(/\s/g, '')}" class="hover:text-medical-600 transition-colors">
                                            {location.phone}
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">✉️</span>
                                        <a href="mailto:{location.email}" class="hover:text-medical-600 transition-colors">
                                            {location.email}
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">🕐</span>
                                        <span>{location.hours}</span>
                                    </div>
                                </div>
                            </div>
                        {/each}
                    </div>
                </div>

                <!-- Contact Form -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Napisz do nas</h2>

                    {#if submitted}
                        <div class="bg-green-50 border border-green-200 rounded-xl p-8 text-center">
                            <div class="text-4xl mb-4">✅</div>
                            <h3 class="text-xl font-semibold text-green-800 mb-2">
                                Wiadomość wysłana!
                            </h3>
                            <p class="text-green-600">
                                Dziękujemy za kontakt. Odpowiemy najszybciej jak to możliwe.
                            </p>
                        </div>
                    {:else}
                        <form onsubmit={handleSubmit} class="space-y-6">
                            <div class="grid sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Imię i nazwisko *
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        bind:value={formData.name}
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                               focus:ring-2 focus:ring-medical-500 focus:border-medical-500
                                               transition-colors"
                                        placeholder="Jan Kowalski"
                                    />
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email *
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        bind:value={formData.email}
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                               focus:ring-2 focus:ring-medical-500 focus:border-medical-500
                                               transition-colors"
                                        placeholder="jan@example.com"
                                    />
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Telefon
                                    </label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        bind:value={formData.phone}
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                               focus:ring-2 focus:ring-medical-500 focus:border-medical-500
                                               transition-colors"
                                        placeholder="+48 123 456 789"
                                    />
                                </div>
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                        Temat
                                    </label>
                                    <select
                                        id="subject"
                                        bind:value={formData.subject}
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                               focus:ring-2 focus:ring-medical-500 focus:border-medical-500
                                               transition-colors"
                                    >
                                        <option value="">Wybierz temat</option>
                                        <option value="appointment">Rezerwacja wizyty</option>
                                        <option value="question">Pytanie ogólne</option>
                                        <option value="complaint">Reklamacja</option>
                                        <option value="cooperation">Współpraca</option>
                                        <option value="other">Inne</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Wiadomość *
                                </label>
                                <textarea
                                    id="message"
                                    bind:value={formData.message}
                                    required
                                    rows="5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                           focus:ring-2 focus:ring-medical-500 focus:border-medical-500
                                           transition-colors resize-none"
                                    placeholder="Treść wiadomości..."
                                ></textarea>
                            </div>

                            <div class="flex items-start gap-3">
                                <input
                                    type="checkbox"
                                    id="consent"
                                    required
                                    class="mt-1 w-4 h-4 text-medical-600 border-gray-300 rounded
                                           focus:ring-medical-500"
                                />
                                <label for="consent" class="text-sm text-gray-600">
                                    Wyrażam zgodę na przetwarzanie moich danych osobowych zgodnie z
                                    <a href="/polityka-prywatnosci" class="text-medical-600 hover:underline">polityką prywatności</a>. *
                                </label>
                            </div>

                            <button
                                type="submit"
                                disabled={isSubmitting}
                                class="w-full px-8 py-4 bg-medical-600 text-white font-semibold rounded-lg
                                       hover:bg-medical-700 transition-colors
                                       focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-medical-500/50
                                       disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {isSubmitting ? 'Wysyłanie...' : 'Wyślij wiadomość'}
                            </button>
                        </form>
                    {/if}
                </div>
            </div>
        </div>
    </section>

    <!-- Map -->
    <section class="h-96 bg-gray-200">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d156388.35438500787!2d20.921111!3d52.233333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ecc669a869f01%3A0x72f0be2a88ead3fc!2sWarszawa!5e0!3m2!1spl!2spl!4v1234567890"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Mapa lokalizacji MedVita"
        ></iframe>
    </section>
</MainLayout>
