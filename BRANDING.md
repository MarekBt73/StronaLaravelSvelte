# Branding: Klinika MedVita

## 1. Podstawowe informacje

| Element | Wartość |
|---------|---------|
| **Nazwa** | MedVita - Centrum Zdrowia |
| **Slogan** | "Twoje zdrowie, nasza misja" |
| **Adres** | ul. Zdrowa 15, 00-001 Warszawa |
| **Telefon** | +48 22 123 45 67 |
| **Email** | kontakt@medvita.pl |
| **Godziny** | Pon-Pt: 8:00-20:00, Sob: 9:00-14:00 |

## 2. Specjalizacje i Usługi

### Lekarze specjaliści
| Specjalizacja | Lekarz (fikcyjny) | Cena konsultacji |
|---------------|-------------------|------------------|
| Internista | dr Anna Kowalska | 200 zł |
| Kardiolog | dr Piotr Nowak | 280 zł |
| Dermatolog | dr Maria Wiśniewska | 250 zł |
| Ginekolog | dr Katarzyna Zielińska | 250 zł |
| Ortopeda | dr Tomasz Lewandowski | 260 zł |
| Pediatra | dr Joanna Kamińska | 180 zł |

### Usługi diagnostyczne
| Usługa | Czas trwania | Cena |
|--------|--------------|------|
| USG jamy brzusznej | 30 min | 180 zł |
| EKG | 15 min | 80 zł |
| Echo serca | 45 min | 350 zł |
| RTG | 20 min | 120 zł |
| Badania laboratoryjne | - | od 50 zł |

### Pakiety profilaktyczne
- Pakiet "Zdrowe Serce" - 450 zł
- Pakiet "Kobieta 40+" - 550 zł
- Pakiet "Mężczyzna 40+" - 520 zł
- Pakiet "Check-up podstawowy" - 350 zł

## 3. Kolorystyka

```css
:root {
  /* Kolory główne */
  --primary-500: #0891B2;      /* Cyjan/Teal - główny */
  --primary-600: #0E7490;      /* Ciemniejszy dla hover */
  --primary-700: #155E75;      /* Najciemniejszy */
  --primary-100: #CFFAFE;      /* Jasny teal dla tła */

  /* Kolory akcentowe */
  --accent-500: #10B981;       /* Zielony - sukces/zdrowie */
  --accent-600: #059669;

  /* Neutralne */
  --gray-50: #F9FAFB;
  --gray-100: #F3F4F6;
  --gray-600: #4B5563;
  --gray-900: #111827;

  /* Semantyczne */
  --success: #10B981;
  --warning: #F59E0B;
  --error: #EF4444;
}
```

### Tailwind Config
```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#ECFEFF',
          100: '#CFFAFE',
          200: '#A5F3FC',
          300: '#67E8F9',
          400: '#22D3EE',
          500: '#0891B2',  // Główny
          600: '#0E7490',
          700: '#155E75',
          800: '#164E63',
          900: '#083344',
        },
        accent: {
          500: '#10B981',
          600: '#059669',
        }
      }
    }
  }
}
```

## 4. Typografia

| Element | Font | Waga |
|---------|------|------|
| Nagłówki | Inter | 600-700 |
| Body | Inter | 400 |
| Akcenty | Inter | 500 |

```html
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
```

## 5. Ikony i Grafika

- **Styl ikon:** Lucide Icons / Heroicons (outline)
- **Zdjęcia:** Unsplash (medical, healthcare)
- **Ilustracje:** Minimalistyczne, line-art style

## 6. Ton komunikacji

- Profesjonalny ale ciepły
- Budujący zaufanie
- Empatyczny
- Unikamy żargonu medycznego w komunikacji z pacjentami

## 7. Przykładowe teksty

### Hero Section
> **Kompleksowa opieka medyczna w jednym miejscu**
>
> MedVita to nowoczesne centrum zdrowia, gdzie doświadczeni specjaliści
> i zaawansowana diagnostyka spotykają się, by zadbać o Twoje zdrowie.

### O nas (krótko)
> Od 2015 roku pomagamy mieszkańcom Warszawy dbać o zdrowie.
> Nasz zespół to 12 doświadczonych specjalistów, którzy rocznie
> przyjmują ponad 15 000 pacjentów.

### CTA
- "Umów wizytę online"
- "Zadzwoń teraz"
- "Sprawdź dostępne terminy"

## 8. Decyzje techniczne

| Funkcja | Decyzja | Uzasadnienie |
|---------|---------|--------------|
| **Płatności** | ❌ Nie | Projekt demo - mockup płatności |
| **SMS** | ❌ Nie | Tylko email (fikcyjny) |
| **Języki** | 🇵🇱 Polski | Jeden język wystarczy na demo |
| **Mapy** | ✅ Google Maps | Embed fikcyjnej lokalizacji |
| **Rezerwacje** | ✅ Pełny flow | Pokazowy, bez realnych powiadomień |

## 9. Fikcyjne dane do seedera

### Lekarze (6 osób)
```php
$doctors = [
    [
        'name' => 'dr n. med. Anna Kowalska',
        'specialization' => 'Internista',
        'title' => 'Specjalista chorób wewnętrznych',
        'experience_years' => 15,
        'bio' => 'Absolwentka Warszawskiego Uniwersytetu Medycznego...',
    ],
    [
        'name' => 'dr Piotr Nowak',
        'specialization' => 'Kardiolog',
        'title' => 'Specjalista kardiolog',
        'experience_years' => 12,
        'bio' => 'Specjalizuje się w diagnostyce i leczeniu...',
    ],
    // ... kolejni
];
```

### Godziny pracy (domyślne)
```php
$defaultSchedule = [
    'monday'    => ['08:00', '20:00'],
    'tuesday'   => ['08:00', '20:00'],
    'wednesday' => ['08:00', '20:00'],
    'thursday'  => ['08:00', '20:00'],
    'friday'    => ['08:00', '20:00'],
    'saturday'  => ['09:00', '14:00'],
    'sunday'    => null, // zamknięte
];
```

---

*Ten dokument służy jako źródło prawdy dla wszystkich tekstów i stylów w projekcie.*
