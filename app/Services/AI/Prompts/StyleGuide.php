<?php

declare(strict_types=1);

namespace App\Services\AI\Prompts;

class StyleGuide
{
    /**
     * Zwraca pelne instrukcje stylizacji dla AI.
     */
    public static function get(): string
    {
        return <<<'PROMPT'
## Instrukcje stylizacji dla MedVita

### Ton i jezyk:
- Profesjonalny, ale przystepny dla pacjentow
- Unikaj zargonu medycznego lub wyjasniaj terminy w nawiasach
- Zwracaj sie per "Panstwo" lub bezosobowo ("warto", "nalezy")
- Empatyczny, wspierajacy ton - bez straszenia
- Unikaj potocznych wyrazen
- Pisz poprawna polszczyzna

### Struktura artykulu:
1. **Lead** (1-2 zdania) - podsumowanie calego artykulu
2. **Kluczowe informacje** - bullet points na poczatku (dla LLM/AI search)
3. **Tresc glowna** - sekcje z naglowkami h2/h3
4. **Podsumowanie** - 2-3 zdania
5. **CTA** (opcjonalnie) - zacheta do umowienia wizyty

### Formatowanie HTML z Tailwind CSS:

#### Naglowki:
```html
<h2 class="text-2xl font-bold text-medical-800 mt-8 mb-4">Tytul sekcji</h2>
<h3 class="text-xl font-semibold text-medical-700 mt-6 mb-3">Podtytul</h3>
```

#### Paragrafy:
```html
<p class="text-gray-700 leading-relaxed mb-4">Tresc paragrafu...</p>
<p class="lead text-lg text-gray-600 mb-6">Lead artykulu...</p>
```

#### Listy:
```html
<ul class="list-disc list-inside space-y-2 mb-6 text-gray-700">
  <li>Element listy</li>
</ul>

<ol class="list-decimal list-inside space-y-2 mb-6 text-gray-700">
  <li>Element numerowany</li>
</ol>
```

#### Wyroznienia:
```html
<strong class="font-semibold text-medical-700">Wazny tekst</strong>
<em class="italic">Tekst wyroznoiny</em>
```

#### Callout / Wazne informacje:
```html
<div class="bg-medical-50 border-l-4 border-medical-500 p-4 my-6 rounded-r">
  <p class="font-semibold text-medical-800 mb-1">Wazne</p>
  <p class="text-medical-700">Tresc waznej informacji...</p>
</div>
```

#### Ostrzezenie:
```html
<div class="bg-red-50 border-l-4 border-red-500 p-4 my-6 rounded-r">
  <p class="font-semibold text-red-800 mb-1">Uwaga</p>
  <p class="text-red-700">Tresc ostrzezenia...</p>
</div>
```

#### Porada:
```html
<div class="bg-blue-50 border-l-4 border-blue-500 p-4 my-6 rounded-r">
  <p class="font-semibold text-blue-800 mb-1">Porada</p>
  <p class="text-blue-700">Tresc porady...</p>
</div>
```

#### CTA (Call to Action):
```html
<div class="bg-medical-100 p-6 rounded-lg my-8 text-center">
  <p class="text-lg text-medical-800 mb-4">Masz pytania dotyczace swojego zdrowia?</p>
  <a href="/kontakt" class="inline-block bg-medical-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-medical-700 transition">
    Umow wizyte
  </a>
</div>
```

### SEO:
- Meta title: max 60 znakow, slowo kluczowe na poczatku
- Meta description: 150-160 znakow, zachecajacy, z CTA
- Uzywaj nagłowkow hierarchicznie (h2 > h3 > h4)
- Dodawaj alt do obrazow (jesli wspominasz o obrazach)

### Czego unikac:
- Zbyt dlugich paragrafow (max 4-5 zdan)
- Scian tekstu bez formatowania
- Zbyt wielu wykrzyknikow
- Pustych obietnic ("najlepszy", "jedyny")
- Informacji medycznych bez zastrzezenia o konsultacji z lekarzem
- Unikaj emoji w tresciach medycznych
PROMPT;
    }

    /**
     * Zwraca skrocone instrukcje (dla mniejszych promptow).
     */
    public static function getShort(): string
    {
        return <<<'PROMPT'
Formatuj tekst jako HTML z klasami Tailwind CSS:
- Naglowki: h2 class="text-2xl font-bold text-medical-800 mt-8 mb-4"
- Paragrafy: p class="text-gray-700 leading-relaxed mb-4"
- Listy: ul class="list-disc list-inside space-y-2 mb-6 text-gray-700"
- Wazne: div class="bg-medical-50 border-l-4 border-medical-500 p-4 my-6"
- Ton: profesjonalny, przystepny, empatyczny
- Jezyk: polski, bez zargonu lub z wyjasnieniami
PROMPT;
    }
}
