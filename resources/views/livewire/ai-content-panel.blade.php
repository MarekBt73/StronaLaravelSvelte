<div
    class="space-y-4"
    x-data="{
        init() {
            // Listener dla wstawiania treści do TinyMCE
            Livewire.on('insertContent', (content) => {
                const contentToInsert = Array.isArray(content) ? content[0] : content;

                // Znajdź edytor TinyMCE na stronie
                if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
                    tinymce.activeEditor.setContent(contentToInsert);
                    tinymce.activeEditor.fire('change');
                } else {
                    // Fallback - znajdź textarea content
                    const textarea = document.querySelector('textarea[name=\'data.content\']');
                    if (textarea) {
                        textarea.value = contentToInsert;
                        textarea.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            });

            // Listener dla SEO
            Livewire.on('seoGenerated', (data) => {
                const seoData = Array.isArray(data) ? data[0] : data;

                // Wypełnij pola SEO
                if (seoData.meta_title) {
                    const titleInput = document.querySelector('input[name=\'data.meta_title\']');
                    if (titleInput) {
                        titleInput.value = seoData.meta_title;
                        titleInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
                if (seoData.meta_description) {
                    const descTextarea = document.querySelector('textarea[name=\'data.meta_description\']');
                    if (descTextarea) {
                        descTextarea.value = seoData.meta_description;
                        descTextarea.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
                if (seoData.keywords) {
                    const keywordsInput = document.querySelector('input[name=\'data.meta_keywords\']');
                    if (keywordsInput) {
                        keywordsInput.value = seoData.keywords;
                        keywordsInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            });
        }
    }"
>
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <x-heroicon-o-sparkles class="w-5 h-5 text-primary-500" />
            <span class="font-medium text-gray-900 dark:text-white">AI Content Assistant</span>
        </div>
        @if($tokensUsed)
            <span class="text-xs text-gray-500">
                Tokeny: {{ number_format($tokensUsed) }}
            </span>
        @endif
    </div>

    {{-- Provider & Action Selection --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Provider
            </label>
            <select
                wire:model="provider"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
            >
                @foreach($this->getAvailableProviders() as $key => $name)
                    <option value="{{ $key }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Akcja
            </label>
            <select
                wire:model="action"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
            >
                @foreach($this->getAvailableActions() as $key => $name)
                    <option value="{{ $key }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Options for article generation --}}
    @if($action === 'generate_article')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ton
                </label>
                <select
                    wire:model="tone"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                >
                    @foreach($this->getAvailableTones() as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Dlugosc
                </label>
                <select
                    wire:model="length"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                >
                    @foreach($this->getAvailableLengths() as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input
                type="checkbox"
                wire:model="includeCta"
                id="include-cta"
                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
            >
            <label for="include-cta" class="text-sm text-gray-700 dark:text-gray-300">
                Dodaj sekcje CTA (wezwanie do dzialania)
            </label>
        </div>
    @endif

    {{-- Prompt Input --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            @if($action === 'generate_article')
                Temat artykulu
            @else
                Tekst do przetworzenia
            @endif
        </label>
        <textarea
            wire:model="prompt"
            rows="4"
            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
            placeholder="@if($action === 'generate_article')Np. Profilaktyka chorob serca u osob po 40 roku zycia. Uwzglednij: diete, aktywnosc fizyczna, badania kontrolne.@else Wklej tutaj tekst do przetworzenia... @endif"
        ></textarea>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-2">
        <button
            wire:click="generate"
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
            <span wire:loading.remove wire:target="generate">
                <x-heroicon-o-sparkles class="w-4 h-4" />
            </span>
            <span wire:loading wire:target="generate">
                <x-heroicon-o-arrow-path class="w-4 h-4 animate-spin" />
            </span>
            <span wire:loading.remove wire:target="generate">Generuj</span>
            <span wire:loading wire:target="generate">Generowanie...</span>
        </button>

        @if($action === 'generate_article')
            <button
                wire:click="suggestTitles"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 transition"
            >
                <x-heroicon-o-light-bulb class="w-4 h-4" />
                Sugeruj tytuly
            </button>
        @endif

        <button
            wire:click="generateSEO"
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 transition"
            title="Wygeneruj meta tagi SEO na podstawie tresci artykulu"
        >
            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
            Generuj SEO
        </button>
    </div>

    {{-- Result Panel --}}
    @if($result)
        <div class="mt-4 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-2 flex items-center justify-between border-b border-gray-200 dark:border-gray-600">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Wynik
                </span>
                <div class="flex gap-2">
                    <button
                        wire:click="insertToEditor"
                        class="inline-flex items-center gap-1 px-3 py-1 bg-primary-600 text-white rounded text-xs font-medium hover:bg-primary-700 transition"
                    >
                        <x-heroicon-o-arrow-down-on-square class="w-3 h-3" />
                        Wstaw do edytora
                    </button>
                    <button
                        wire:click="generate"
                        class="inline-flex items-center gap-1 px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded text-xs font-medium hover:bg-gray-300 dark:hover:bg-gray-500 transition"
                    >
                        <x-heroicon-o-arrow-path class="w-3 h-3" />
                        Regeneruj
                    </button>
                    <button
                        wire:click="clearResult"
                        class="inline-flex items-center gap-1 px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded text-xs font-medium hover:bg-gray-300 dark:hover:bg-gray-500 transition"
                    >
                        <x-heroicon-o-x-mark class="w-3 h-3" />
                    </button>
                </div>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto bg-white dark:bg-gray-900">
                <div class="prose prose-sm dark:prose-invert max-w-none">
                    {!! $result !!}
                </div>
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div
        wire:loading
        wire:target="generate, generateSEO, suggestTitles"
        class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl flex items-center gap-3">
            <x-heroicon-o-arrow-path class="w-6 h-6 text-primary-500 animate-spin" />
            <span class="text-gray-700 dark:text-gray-300">Generowanie tresci AI...</span>
        </div>
    </div>
</div>
