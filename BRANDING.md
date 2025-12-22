# Branding: Klinika MedVita

> **Inspiracja:** Analiza UX strony grupazdrowie.pl - zachowujemy sprawdzone wzorce,
> ale z nowoczesnym stackiem (szybkość, lepszy UX rezerwacji, zaawansowane SEO).

---

## 1. Podstawowe informacje

| Element | Wartość |
|---------|---------|
| **Nazwa** | MedVita - Centrum Zdrowia |
| **Slogan** | "Twoje zdrowie, nasza misja" |
| **Email** | kontakt@medvita.pl |
| **Strona** | medvita.pl (fikcyjna) |

### Placówki (Multi-Location)

| Placówka | Adres | Telefon |
|----------|-------|---------|
| **MedVita Centrum** | ul. Zdrowa 15, 00-001 Warszawa | +48 22 123 45 67 |
| **MedVita Mokotów** | ul. Puławska 200, 02-670 Warszawa | +48 22 234 56 78 |
| **MedVita Ursynów** | ul. Wąwozowa 18, 02-796 Warszawa | +48 22 345 67 89 |

**Godziny otwarcia:** Pon-Pt: 8:00-20:00, Sob: 9:00-14:00

---

## 2. Architektura UX (wzór: grupazdrowie.pl)

### Kluczowe moduły strony

```
┌─────────────────────────────────────────────────────────────────────┐
│  TOP BAR: Telefon | Email | Social Icons                           │
├─────────────────────────────────────────────────────────────────────┤
│  HEADER: Logo | Menu (Usługi, Lekarze, Placówki, Blog) | [Umów]    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  HERO SECTION (full-width image)                                    │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  "Znajdź swojego lekarza"                                    │   │
│  │  [Specjalizacja ▼] [Lekarz ▼] [Placówka ▼] [SZUKAJ]         │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│  USŁUGI GRID (4 kolumny)                                            │
│  ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐                                   │
│  │ POZ │ │Spec.│ │Diagn│ │Labor│                                   │
│  └─────┘ └─────┘ └─────┘ └─────┘                                   │
├─────────────────────────────────────────────────────────────────────┤
│  DLACZEGO MY? (3 kolumny: Doświadczenie | Nowoczesność | Dostęp)   │
├─────────────────────────────────────────────────────────────────────┤
│  NASI LEKARZE (carousel / grid 3 kolumny)                           │
├─────────────────────────────────────────────────────────────────────┤
│  AKTUALNOŚCI / BLOG (3 najnowsze)                                   │
├─────────────────────────────────────────────────────────────────────┤
│  MAPA PLACÓWEK (interaktywna)                                       │
├─────────────────────────────────────────────────────────────────────┤
│  FOOTER: Kontakt | Menu | Social | Newsletter                       │
└─────────────────────────────────────────────────────────────────────┘
```

### Komponenty Svelte (priorytet)

| Komponent | Opis | Priorytet |
|-----------|------|-----------|
| `HeroSearch.svelte` | Live search: specjalizacja/lekarz/placówka | P0 |
| `BookingWizard.svelte` | 5-krokowy wizard rezerwacji (wewnętrzny, nie external!) | P0 |
| `ServiceGrid.svelte` | Kafelki usług z ikonami | P1 |
| `DoctorCard.svelte` | Karta lekarza ze zdjęciem | P1 |
| `LocationMap.svelte` | Mapa z placówkami | P2 |
| `BlogPreview.svelte` | Preview artykułu | P2 |

---

## 3. Kolorystyka (zaktualizowana)

> Styl "medical blue" - bezpieczny, profesjonalny, budujący zaufanie.

```css
:root {
  /* Kolory główne - Medical Blue */
  --medical-50: #f0f9ff;   /* Tło sekcji */
  --medical-100: #e0f2fe;
  --medical-500: #0ea5e9;  /* Przyciski secondary */
  --medical-600: #0284c7;  /* Brand color - Header, Primary Buttons */
  --medical-700: #0369a1;
  --medical-800: #075985;  /* Tekst nagłówków */
  --medical-900: #0c4a6e;  /* Footer */

  /* Akcent - Zielony (dostępność, sukces) */
  --accent-400: #a3e635;
  --accent-500: #84cc16;   /* Status "Dostępny" */
  --accent-600: #65a30d;

  /* Neutralne */
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-600: #4b5563;
  --gray-800: #1f2937;
  --gray-900: #111827;

  /* Semantyczne */
  --success: #22c55e;
  --warning: #f59e0b;
  --error: #ef4444;
  --info: #3b82f6;
}
```

### Tailwind Config (finalna)
```js
// tailwind.config.js
export default {
  theme: {
    extend: {
      colors: {
        medical: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',  // PRIMARY
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
        },
        accent: {
          400: '#a3e635',
          500: '#84cc16',
          600: '#65a30d',
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      }
    }
  }
}
```

---

## 4. Typografia

| Element | Font | Waga | Rozmiar |
|---------|------|------|---------|
| H1 (Hero) | Inter | 700 | 48px / 3rem |
| H2 (Sekcje) | Inter | 600 | 36px / 2.25rem |
| H3 (Karty) | Inter | 600 | 24px / 1.5rem |
| Body | Inter | 400 | 16px / 1rem |
| Small | Inter | 400 | 14px / 0.875rem |
| Button | Inter | 500 | 16px / 1rem |

```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
```

---

## 5. Specjalizacje i Usługi

### Lekarze specjaliści (6 osób)

| Specjalizacja | Lekarz | Placówki | Cena |
|---------------|--------|----------|------|
| Internista | dr n. med. Anna Kowalska | Centrum, Mokotów | 200 zł |
| Kardiolog | dr Piotr Nowak | Centrum | 280 zł |
| Dermatolog | dr Maria Wiśniewska | Centrum, Ursynów | 250 zł |
| Ginekolog | dr Katarzyna Zielińska | Mokotów, Ursynów | 250 zł |
| Ortopeda | dr Tomasz Lewandowski | Centrum | 260 zł |
| Pediatra | dr Joanna Kamińska | Wszystkie | 180 zł |

### Usługi diagnostyczne (zależne od placówki)

| Usługa | Czas | Cena | Placówki |
|--------|------|------|----------|
| USG jamy brzusznej | 30 min | 180 zł | Centrum, Mokotów |
| EKG | 15 min | 80 zł | Wszystkie |
| Echo serca | 45 min | 350 zł | Centrum |
| RTG | 20 min | 120 zł | Centrum |
| Badania laboratoryjne | - | od 50 zł | Wszystkie |

### Pakiety profilaktyczne
- Pakiet "Zdrowe Serce" - 450 zł
- Pakiet "Kobieta 40+" - 550 zł
- Pakiet "Mężczyzna 40+" - 520 zł
- Pakiet "Check-up podstawowy" - 350 zł

---

## 6. Baza danych (rozszerzona)

### Nowe tabele

```
locations                    doctor_location (pivot)
├── id                       ├── doctor_id (FK)
├── name                     ├── location_id (FK)
├── slug                     └── schedule_settings (JSON)
├── address
├── city                     service_location (pivot)
├── zip_code                 ├── service_id (FK)
├── phone                    └── location_id (FK)
├── google_maps_embed
├── is_active
└── created_at
```

### Migracja: locations
```php
Schema::create('locations', function (Blueprint $table) {
    $table->id();
    $table->string('name');           // "MedVita Centrum"
    $table->string('slug')->unique(); // "centrum"
    $table->string('address');        // "ul. Zdrowa 15"
    $table->string('city');           // "Warszawa"
    $table->string('zip_code');       // "00-001"
    $table->string('phone');
    $table->text('google_maps_embed')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Migracja: doctor_location (pivot)
```php
Schema::create('doctor_location', function (Blueprint $table) {
    $table->id();
    $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
    $table->foreignId('location_id')->constrained()->cascadeOnDelete();
    $table->json('schedule_settings')->nullable(); // {"monday": ["08:00", "16:00"]}
    $table->timestamps();

    $table->unique(['doctor_id', 'location_id']);
});
```

---

## 7. Booking Wizard (5 kroków)

```
┌─────────────────────────────────────────────────────────────────────┐
│  KROK 1: Wybór usługi                                               │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ [Grid usług / wyszukiwarka]                                  │   │
│  │ → Wybieram: Kardiolog - Konsultacja                         │   │
│  └─────────────────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────────────────┤
│  KROK 2: Wybór lekarza i placówki                                   │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ [Lista lekarzy (filtrowana po usłudze)]                      │   │
│  │ dr Piotr Nowak - MedVita Centrum                            │   │
│  └─────────────────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────────────────┤
│  KROK 3: Wybór terminu                                              │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ [Kalendarz tygodniowy]  [Dostępne godziny]                   │   │
│  │      Pon 23.12           09:00 ✓  10:00 ✓  11:00 ✗          │   │
│  └─────────────────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────────────────┤
│  KROK 4: Dane pacjenta                                              │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Imię: [________]  Nazwisko: [________]                       │   │
│  │ Email: [________]  Telefon: [________]                       │   │
│  │ [ ] Akceptuję regulamin i politykę prywatności              │   │
│  └─────────────────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────────────────┤
│  KROK 5: Podsumowanie                                               │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Usługa: Kardiolog - Konsultacja (280 zł)                     │   │
│  │ Lekarz: dr Piotr Nowak                                       │   │
│  │ Placówka: MedVita Centrum, ul. Zdrowa 15                     │   │
│  │ Termin: 23.12.2024, godz. 10:00                              │   │
│  │                                                               │   │
│  │           [POTWIERDŹ REZERWACJĘ]                             │   │
│  └─────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 8. SEO & Schema.org

### Strona główna - MedicalBusiness + departments
```json
{
  "@context": "https://schema.org",
  "@type": "MedicalBusiness",
  "name": "MedVita - Centrum Zdrowia",
  "url": "https://medvita.pl",
  "logo": "https://medvita.pl/images/logo.png",
  "telephone": "+48221234567",
  "department": [
    {
      "@type": "MedicalClinic",
      "name": "MedVita Centrum",
      "address": { "@type": "PostalAddress", "streetAddress": "ul. Zdrowa 15", "addressLocality": "Warszawa" }
    },
    {
      "@type": "MedicalClinic",
      "name": "MedVita Mokotów",
      "address": { "@type": "PostalAddress", "streetAddress": "ul. Puławska 200", "addressLocality": "Warszawa" }
    }
  ]
}
```

### Strona lekarza - Physician
```json
{
  "@context": "https://schema.org",
  "@type": "Physician",
  "name": "dr Piotr Nowak",
  "medicalSpecialty": "Kardiologia",
  "worksFor": { "@type": "MedicalBusiness", "name": "MedVita" },
  "availableService": { "@type": "MedicalProcedure", "name": "Konsultacja kardiologiczna" }
}
```

### Landing Pages SEO (przewaga nad konkurencją)
Zamiast krótkich opisów - tworzymy **bogate strony** `/specjalizacje/kardiolog`:
- H1: "Kardiolog Warszawa - MedVita"
- Sekcja FAQ (schema FAQPage)
- Lista lekarzy z tej specjalizacji
- Powiązane usługi diagnostyczne

---

## 9. Przykładowe teksty

### Hero Section
> **Kompleksowa opieka medyczna w Warszawie**
>
> 3 placówki | 12 specjalistów | Rezerwacja online 24/7

### O nas
> Od 2015 roku pomagamy mieszkańcom Warszawy dbać o zdrowie.
> Nasz zespół to 12 doświadczonych specjalistów, którzy rocznie
> przyjmują ponad 15 000 pacjentów w 3 placówkach.

### CTA Buttons
- "Umów wizytę online" (primary)
- "Znajdź lekarza" (secondary)
- "Zadzwoń: +48 22 123 45 67" (ghost)

### Microcopy (UX)
- Status dostępności: "Najbliższy wolny termin: jutro, 10:00"
- Potwierdzenie: "Rezerwacja potwierdzona! Sprawdź email."
- Błąd: "Ups! Ten termin został właśnie zajęty. Wybierz inny."

---

## 10. Decyzje techniczne

| Funkcja | Decyzja | Uwagi |
|---------|---------|-------|
| **Płatności** | ❌ Mockup | Button bez funkcji |
| **SMS** | ❌ Nie | Tylko email (fikcyjny) |
| **Języki** | 🇵🇱 Polski | Single language |
| **Mapy** | ✅ Google Maps Embed | Dla każdej placówki |
| **Rezerwacje** | ✅ Pełny flow | 5-krokowy wizard |
| **Live Search** | ✅ Tak | HeroSearch component |
| **Blog** | ✅ Tak | Filament + SEO |
| **Multi-location** | ✅ Tak | 3 placówki |

---

## 11. Dane do Seedera

### Placówki
```php
$locations = [
    [
        'name' => 'MedVita Centrum',
        'slug' => 'centrum',
        'address' => 'ul. Zdrowa 15',
        'city' => 'Warszawa',
        'zip_code' => '00-001',
        'phone' => '+48 22 123 45 67',
    ],
    [
        'name' => 'MedVita Mokotów',
        'slug' => 'mokotow',
        'address' => 'ul. Puławska 200',
        'city' => 'Warszawa',
        'zip_code' => '02-670',
        'phone' => '+48 22 234 56 78',
    ],
    [
        'name' => 'MedVita Ursynów',
        'slug' => 'ursynow',
        'address' => 'ul. Wąwozowa 18',
        'city' => 'Warszawa',
        'zip_code' => '02-796',
        'phone' => '+48 22 345 67 89',
    ],
];
```

### Lekarze
```php
$doctors = [
    [
        'name' => 'dr n. med. Anna Kowalska',
        'slug' => 'anna-kowalska',
        'specialization' => 'Internista',
        'title' => 'Specjalista chorób wewnętrznych',
        'experience_years' => 15,
        'bio' => 'Absolwentka Warszawskiego Uniwersytetu Medycznego. Specjalizuje się w diagnostyce i leczeniu chorób wewnętrznych.',
        'locations' => ['centrum', 'mokotow'],
    ],
    [
        'name' => 'dr Piotr Nowak',
        'slug' => 'piotr-nowak',
        'specialization' => 'Kardiolog',
        'title' => 'Specjalista kardiolog',
        'experience_years' => 12,
        'bio' => 'Ekspert w diagnostyce i leczeniu chorób układu krążenia. Wykonuje badania EKG i Echo serca.',
        'locations' => ['centrum'],
    ],
    [
        'name' => 'dr Maria Wiśniewska',
        'slug' => 'maria-wisniewska',
        'specialization' => 'Dermatolog',
        'title' => 'Specjalista dermatolog',
        'experience_years' => 10,
        'bio' => 'Specjalizuje się w dermatologii estetycznej i leczeniu chorób skóry.',
        'locations' => ['centrum', 'ursynow'],
    ],
    [
        'name' => 'dr Katarzyna Zielińska',
        'slug' => 'katarzyna-zielinska',
        'specialization' => 'Ginekolog',
        'title' => 'Specjalista ginekolog-położnik',
        'experience_years' => 18,
        'bio' => 'Doświadczony ginekolog-położnik z wieloletnią praktyką kliniczną.',
        'locations' => ['mokotow', 'ursynow'],
    ],
    [
        'name' => 'dr Tomasz Lewandowski',
        'slug' => 'tomasz-lewandowski',
        'specialization' => 'Ortopeda',
        'title' => 'Specjalista ortopeda-traumatolog',
        'experience_years' => 14,
        'bio' => 'Specjalista w leczeniu urazów i chorób narządu ruchu.',
        'locations' => ['centrum'],
    ],
    [
        'name' => 'dr Joanna Kamińska',
        'slug' => 'joanna-kaminska',
        'specialization' => 'Pediatra',
        'title' => 'Specjalista pediatra',
        'experience_years' => 20,
        'bio' => 'Doświadczony pediatra, przyjmuje dzieci od urodzenia do 18 roku życia.',
        'locations' => ['centrum', 'mokotow', 'ursynow'],
    ],
];
```

---

*Ten dokument służy jako źródło prawdy dla wszystkich tekstów, stylów i danych w projekcie.*
