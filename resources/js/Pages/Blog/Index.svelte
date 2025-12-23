<script>
    import { router } from '@inertiajs/svelte';
    import MainLayout from '../../Components/Layout/MainLayout.svelte';

    let { articles, categories, filters, currentCategory = null } = $props();

    let searchQuery = $state(filters.search || '');
    let isSearching = $state(false);

    function handleSearch(e) {
        e.preventDefault();
        isSearching = true;
        router.get('/blog', { search: searchQuery, kategoria: filters.kategoria }, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => { isSearching = false; }
        });
    }

    function clearSearch() {
        searchQuery = '';
        router.get('/blog', { kategoria: filters.kategoria }, {
            preserveState: true,
            preserveScroll: true,
        });
    }

    function clearFilters() {
        searchQuery = '';
        router.get('/blog', {}, {
            preserveState: true,
            preserveScroll: true,
        });
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('pl-PL', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    function truncateText(text, maxLength = 150) {
        const stripped = stripHtml(text);
        if (stripped.length <= maxLength) return stripped;
        return stripped.substring(0, maxLength).trim() + '...';
    }
</script>

<MainLayout title={currentCategory ? `${currentCategory.name} - Blog` : 'Blog'}>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4">
                    {#if currentCategory}
                        {currentCategory.name}
                    {:else}
                        Blog MedVita
                    {/if}
                </h1>
                <p class="text-lg text-medical-100 max-w-2xl mx-auto">
                    {#if currentCategory && currentCategory.description}
                        {currentCategory.description}
                    {:else}
                        Aktualności, porady zdrowotne i informacje medyczne od naszych specjalistów
                    {/if}
                </p>
            </div>

            <!-- Search Form -->
            <div class="mt-8 max-w-xl mx-auto">
                <form onsubmit={handleSearch} class="relative">
                    <label for="search" class="sr-only">Szukaj artykułów</label>
                    <input
                        type="search"
                        id="search"
                        bind:value={searchQuery}
                        placeholder="Szukaj artykułów..."
                        class="w-full px-4 py-3 pl-12 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-400"
                    />
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {#if searchQuery}
                        <button
                            type="button"
                            onclick={clearSearch}
                            class="absolute right-14 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            aria-label="Wyczyść wyszukiwanie"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    {/if}
                    <button
                        type="submit"
                        disabled={isSearching}
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-1.5 rounded-md transition-colors disabled:opacity-50"
                    >
                        {isSearching ? 'Szukam...' : 'Szukaj'}
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:gap-8">
                <!-- Sidebar - Categories -->
                <aside class="lg:w-64 flex-shrink-0 mb-8 lg:mb-0">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Kategorie</h2>
                        <nav aria-label="Kategorie bloga">
                            <ul class="space-y-2">
                                <li>
                                    <a
                                        href="/blog"
                                        class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {!filters.kategoria ? 'bg-medical-100 text-medical-700 font-medium' : 'text-gray-600 hover:bg-gray-100'}"
                                    >
                                        <span>Wszystkie</span>
                                        <span class="text-sm bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
                                            {articles.total || 0}
                                        </span>
                                    </a>
                                </li>
                                {#each categories as category}
                                    <li>
                                        <a
                                            href="/blog/kategoria/{category.slug}"
                                            class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {filters.kategoria === category.slug ? 'bg-medical-100 text-medical-700 font-medium' : 'text-gray-600 hover:bg-gray-100'}"
                                        >
                                            <span>{category.name}</span>
                                            <span class="text-sm bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
                                                {category.articles_count}
                                            </span>
                                        </a>
                                    </li>
                                {/each}
                            </ul>
                        </nav>
                    </div>
                </aside>

                <!-- Articles Grid -->
                <main class="flex-1">
                    <!-- Active Filters -->
                    {#if filters.search || filters.kategoria}
                        <div class="mb-6 flex flex-wrap items-center gap-2">
                            <span class="text-sm text-gray-500">Aktywne filtry:</span>
                            {#if filters.search}
                                <span class="inline-flex items-center gap-1 bg-medical-100 text-medical-700 px-3 py-1 rounded-full text-sm">
                                    Szukaj: "{filters.search}"
                                    <button onclick={clearSearch} class="hover:text-medical-900" aria-label="Usuń filtr wyszukiwania">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </span>
                            {/if}
                            {#if filters.kategoria && currentCategory}
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm">
                                    {currentCategory.name}
                                </span>
                            {/if}
                            <button
                                onclick={clearFilters}
                                class="text-sm text-gray-500 hover:text-gray-700 underline"
                            >
                                Wyczyść wszystkie
                            </button>
                        </div>
                    {/if}

                    <!-- Results Count -->
                    <p class="text-gray-600 mb-6">
                        {#if articles.total === 0}
                            Nie znaleziono artykułów
                        {:else if articles.total === 1}
                            Znaleziono 1 artykuł
                        {:else}
                            Znaleziono {articles.total} artykułów
                        {/if}
                    </p>

                    <!-- Articles Grid -->
                    {#if articles.data && articles.data.length > 0}
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            {#each articles.data as article}
                                <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                                    <a href="/blog/{article.slug}" class="block">
                                        {#if article.featured_image}
                                            <div class="aspect-video overflow-hidden">
                                                <img
                                                    src="/storage/{article.featured_image}"
                                                    alt={article.featured_image_alt || article.title}
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                    loading="lazy"
                                                />
                                            </div>
                                        {:else}
                                            <div class="aspect-video bg-gradient-to-br from-medical-100 to-medical-200 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-medical-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                                </svg>
                                            </div>
                                        {/if}
                                        <div class="p-5">
                                            <div class="flex items-center gap-2 mb-3">
                                                {#if article.category}
                                                    <span class="text-xs font-medium text-medical-600 bg-medical-50 px-2 py-1 rounded">
                                                        {article.category.name}
                                                    </span>
                                                {/if}
                                                <span class="text-xs text-gray-400">
                                                    {formatDate(article.published_at)}
                                                </span>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-medical-600 transition-colors line-clamp-2">
                                                {article.title}
                                            </h3>
                                            <p class="text-gray-600 text-sm line-clamp-3">
                                                {article.excerpt || truncateText(article.content)}
                                            </p>
                                            <div class="mt-4 flex items-center justify-between text-sm">
                                                <span class="text-medical-600 font-medium group-hover:underline">
                                                    Czytaj dalej
                                                </span>
                                                {#if article.views > 0}
                                                    <span class="text-gray-400 flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        {article.views}
                                                    </span>
                                                {/if}
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            {/each}
                        </div>

                        <!-- Pagination -->
                        {#if articles.last_page > 1}
                            <nav class="mt-12 flex justify-center" aria-label="Paginacja">
                                <ul class="flex items-center gap-1">
                                    {#if articles.prev_page_url}
                                        <li>
                                            <a
                                                href={articles.prev_page_url}
                                                class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors"
                                                aria-label="Poprzednia strona"
                                            >
                                                &larr; Poprzednia
                                            </a>
                                        </li>
                                    {/if}

                                    <li class="px-4 py-2 text-gray-600">
                                        Strona {articles.current_page} z {articles.last_page}
                                    </li>

                                    {#if articles.next_page_url}
                                        <li>
                                            <a
                                                href={articles.next_page_url}
                                                class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors"
                                                aria-label="Następna strona"
                                            >
                                                Następna &rarr;
                                            </a>
                                        </li>
                                    {/if}
                                </ul>
                            </nav>
                        {/if}
                    {:else}
                        <!-- No Results -->
                        <div class="text-center py-16 bg-white rounded-xl">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Brak wyników</h3>
                            <p class="text-gray-500 mb-6">
                                {#if filters.search}
                                    Nie znaleziono artykułów pasujących do "{filters.search}"
                                {:else}
                                    Nie ma jeszcze artykułów w tej kategorii
                                {/if}
                            </p>
                            <button
                                onclick={clearFilters}
                                class="inline-flex items-center gap-2 bg-medical-600 hover:bg-medical-700 text-white px-6 py-2 rounded-lg transition-colors"
                            >
                                Zobacz wszystkie artykuły
                            </button>
                        </div>
                    {/if}
                </main>
            </div>
        </div>
    </section>
</MainLayout>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
