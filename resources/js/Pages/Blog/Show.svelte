<script>
    import MainLayout from '../../Components/Layout/MainLayout.svelte';

    let { article, related } = $props();

    // Build full URL for OG image - use $derived for reactive values from props
    const baseUrl = typeof window !== 'undefined' ? window.location.origin : '';
    let ogImage = $derived(article.featured_image ? `${baseUrl}/storage/${article.featured_image}` : `${baseUrl}/images/og-default.jpg`);
    const pageUrl = typeof window !== 'undefined' ? window.location.href : '';

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('pl-PL', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function getReadingTime(content) {
        const text = content.replace(/<[^>]*>/g, '');
        const wordCount = text.split(/\s+/).length;
        return Math.max(1, Math.ceil(wordCount / 200));
    }

    function shareOnFacebook() {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`, '_blank', 'width=600,height=400');
    }

    function shareOnTwitter() {
        window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent(article.title)}`, '_blank', 'width=600,height=400');
    }

    function shareOnLinkedIn() {
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(window.location.href)}`, '_blank', 'width=600,height=400');
    }

    function copyLink() {
        navigator.clipboard.writeText(window.location.href);
        alert('Link skopiowany do schowka!');
    }
</script>

<svelte:head>
    <title>{article.meta_title || article.title} | MedVita Blog</title>
    <meta name="description" content={article.meta_description || article.excerpt || ''} />

    <!-- Open Graph -->
    <meta property="og:type" content="article" />
    <meta property="og:title" content={article.meta_title || article.title} />
    <meta property="og:description" content={article.meta_description || article.excerpt || ''} />
    <meta property="og:image" content={ogImage} />
    <meta property="og:url" content={pageUrl} />
    <meta property="og:site_name" content="MedVita" />
    <meta property="article:published_time" content={article.published_at} />
    {#if article.author}
        <meta property="article:author" content={article.author.name} />
    {/if}

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content={article.meta_title || article.title} />
    <meta name="twitter:description" content={article.meta_description || article.excerpt || ''} />
    <meta name="twitter:image" content={ogImage} />
</svelte:head>

<MainLayout>
    <!-- Article Header -->
    <article>
        <header class="bg-gradient-to-br from-medical-600 to-medical-800 text-white py-12 lg:py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 text-sm text-medical-200">
                        <li>
                            <a href="/" class="hover:text-white transition-colors">Strona główna</a>
                        </li>
                        <li aria-hidden="true">/</li>
                        <li>
                            <a href="/blog" class="hover:text-white transition-colors">Blog</a>
                        </li>
                        {#if article.category}
                            <li aria-hidden="true">/</li>
                            <li>
                                <a href="/blog/kategoria/{article.category.slug}" class="hover:text-white transition-colors">
                                    {article.category.name}
                                </a>
                            </li>
                        {/if}
                    </ol>
                </nav>

                <!-- Category Badge -->
                {#if article.category}
                    <a
                        href="/blog/kategoria/{article.category.slug}"
                        class="inline-block bg-amber-500 text-white text-sm font-medium px-3 py-1 rounded-full mb-4 hover:bg-amber-600 transition-colors"
                    >
                        {article.category.name}
                    </a>
                {/if}

                <!-- Title -->
                <h1 class="text-3xl lg:text-4xl xl:text-5xl font-bold mb-6 leading-tight">
                    {article.title}
                </h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-4 text-medical-100">
                    {#if article.author}
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-medical-500 flex items-center justify-center text-white font-medium">
                                {article.author.name.charAt(0)}
                            </div>
                            <div>
                                <p class="font-medium text-white">{article.author.name}</p>
                                <p class="text-sm">{formatDate(article.published_at)}</p>
                            </div>
                        </div>
                    {/if}

                    <span class="flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {getReadingTime(article.content)} min czytania
                    </span>

                    <span class="flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {article.views} wyświetleń
                    </span>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        {#if article.featured_image}
            <div class="relative -mt-8 mb-8">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <img
                        src="/storage/{article.featured_image}"
                        alt={article.featured_image_alt || article.title}
                        class="w-full rounded-xl shadow-xl"
                    />
                </div>
            </div>
        {/if}

        <!-- Article Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="lg:flex lg:gap-12">
                <!-- Share Sidebar (Desktop) -->
                <aside class="hidden lg:block lg:w-16 flex-shrink-0">
                    <div class="sticky top-24 flex flex-col gap-3">
                        <span class="text-xs text-gray-400 text-center">Udostępnij</span>
                        <button
                            onclick={shareOnFacebook}
                            class="w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-colors"
                            aria-label="Udostępnij na Facebook"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </button>
                        <button
                            onclick={shareOnTwitter}
                            class="w-10 h-10 rounded-full bg-black hover:bg-gray-800 text-white flex items-center justify-center transition-colors"
                            aria-label="Udostępnij na X"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </button>
                        <button
                            onclick={shareOnLinkedIn}
                            class="w-10 h-10 rounded-full bg-blue-700 hover:bg-blue-800 text-white flex items-center justify-center transition-colors"
                            aria-label="Udostępnij na LinkedIn"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </button>
                        <button
                            onclick={copyLink}
                            class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 flex items-center justify-center transition-colors"
                            aria-label="Kopiuj link"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="flex-1">
                    <!-- Excerpt -->
                    {#if article.excerpt}
                        <p class="text-xl text-gray-600 leading-relaxed mb-8 font-medium">
                            {article.excerpt}
                        </p>
                    {/if}

                    <!-- Content -->
                    <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-a:text-medical-600 prose-a:no-underline hover:prose-a:underline">
                        {@html article.content}
                    </div>

                    <!-- Share (Mobile) -->
                    <div class="lg:hidden mt-8 pt-8 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-4">Udostępnij artykuł:</p>
                        <div class="flex gap-3">
                            <button
                                onclick={shareOnFacebook}
                                class="flex-1 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors"
                            >
                                Facebook
                            </button>
                            <button
                                onclick={shareOnTwitter}
                                class="flex-1 py-2 rounded-lg bg-black hover:bg-gray-800 text-white text-sm font-medium transition-colors"
                            >
                                X
                            </button>
                            <button
                                onclick={copyLink}
                                class="flex-1 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition-colors"
                            >
                                Kopiuj link
                            </button>
                        </div>
                    </div>

                    <!-- Tags / Keywords -->
                    {#if article.meta_keywords}
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <p class="text-sm text-gray-500 mb-3">Tagi:</p>
                            <div class="flex flex-wrap gap-2">
                                {#each article.meta_keywords.split(',') as tag}
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                                        {tag.trim()}
                                    </span>
                                {/each}
                            </div>
                        </div>
                    {/if}

                    <!-- Author Box -->
                    {#if article.author}
                        <div class="mt-8 p-6 bg-gray-50 rounded-xl">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-full bg-medical-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                                    {article.author.name.charAt(0)}
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Autor</p>
                                    <h3 class="text-lg font-semibold text-gray-900">{article.author.name}</h3>
                                    <p class="text-gray-600 mt-2">
                                        Specjalista w zespole MedVita. Dzieli się wiedzą medyczną i poradami zdrowotnymi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    </article>

    <!-- Related Articles -->
    {#if related && related.length > 0}
        <section class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Powiązane artykuły</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {#each related as relatedArticle}
                        <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                            <a href="/blog/{relatedArticle.slug}" class="block">
                                {#if relatedArticle.featured_image}
                                    <div class="aspect-video overflow-hidden">
                                        <img
                                            src="/storage/{relatedArticle.featured_image}"
                                            alt={relatedArticle.featured_image_alt || relatedArticle.title}
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                        />
                                    </div>
                                {:else}
                                    <div class="aspect-video bg-gradient-to-br from-medical-100 to-medical-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-medical-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                {/if}
                                <div class="p-5">
                                    <p class="text-xs text-gray-400 mb-2">{formatDate(relatedArticle.published_at)}</p>
                                    <h3 class="font-semibold text-gray-900 group-hover:text-medical-600 transition-colors line-clamp-2">
                                        {relatedArticle.title}
                                    </h3>
                                </div>
                            </a>
                        </article>
                    {/each}
                </div>

                <div class="text-center mt-8">
                    <a
                        href="/blog"
                        class="inline-flex items-center gap-2 text-medical-600 hover:text-medical-700 font-medium"
                    >
                        Zobacz wszystkie artykuły
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    {/if}
</MainLayout>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
