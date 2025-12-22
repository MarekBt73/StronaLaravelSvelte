# Katalog Zasobów Graficznych - MedVita

> Mapowanie plików z folderu `/media` do komponentów strony.

---

## 1. Hero Section (Strona główna)

| Plik | Przeznaczenie | Rozmiar |
|------|---------------|---------|
| `beautiful-female-therapist-clinic.jpg` | **Hero background** - główne zdjęcie | 3MB |
| `beautiful-female-therapist-clinic.png` | Hero (wersja PNG) | 1.4MB |
| `piekna-terapeutka-w-klinice.png` | Hero alternatywne | 1.4MB |

**Rekomendacja:** Użyj `beautiful-female-therapist-clinic.jpg` jako tło Hero z overlay.

---

## 2. Ikony Usług (ServiceGrid)

Okrągłe ikony do kafelków usług na stronie głównej:

| Plik | Usługa | Alt text |
|------|--------|----------|
| `lekarz_rodzinny.png` | POZ / Internista | "Stetoskop - lekarz rodzinny" |
| `laboratorium.png` | Badania laboratoryjne | "Probówka - badania laboratoryjne" |
| `stopmatolog_uslugi.png` | Stomatologia | "Badanie zębów - stomatologia" |
| `szczepienie_uslugi.png` | Szczepienia | "Dziecko podczas szczepienia" |
| `uslugi_pediatry.png` | Pediatria | "Maskotka z stetoskopem - pediatria" |
| `polozna.png` | Położna / Ginekologia | "Stópki noworodka - położna" |
| `profilaktyka_uslugi.png` | Badania profilaktyczne | "Konsultacja lekarska - profilaktyka" |
| `profilaktycznebadania.png` | Pakiety profilaktyczne | "Badania profilaktyczne" |

---

## 3. Zdjęcia Lekarzy (DoctorCard)

Zdjęcia do kart lekarzy - idealne bez tła:

| Plik | Przypisanie (sugerowane) | Alt text |
|------|--------------------------|----------|
| `PIELENGNIARKA-DOBRON_BEZ_TLA.png` | dr Anna Kowalska (Internista) | "dr Anna Kowalska - zdjęcie profilowe" |
| `PIELENGNIARKA-DOBRON_BEZ_TLA_3.png` | dr Maria Wiśniewska (Dermatolog) | "dr Maria Wiśniewska - zdjęcie profilowe" |
| `PIELENGNIARKA-DOBRON_BEZ_TLA_4_max.png` | dr Katarzyna Zielińska (Ginekolog) | "dr Katarzyna Zielińska - zdjęcie profilowe" |
| `PIELENGNIARKA-DOBRON_400x400.png` | dr Joanna Kamińska (Pediatra) | "dr Joanna Kamińska - zdjęcie profilowe" |
| `blond_Pielengniarka.webp` | Alternatywne | "Lekarz specjalista" |

**Uwaga:** Potrzebne zdjęcia męskich lekarzy dla:
- dr Piotr Nowak (Kardiolog)
- dr Tomasz Lewandowski (Ortopeda)

---

## 4. Zdjęcia do sekcji "O nas" / Blog

| Plik | Przeznaczenie |
|------|---------------|
| `ai_logo_medicyn.jpg` | Sekcja diagnostyka / RTG |
| `portrait-nurse-holding-clipboard.jpg` | Sekcja "Nasz zespół" |
| `portret-pielegniarki-w-szpitalu.png` | Blog / Aktualności |
| `side-view-doctor-checking-patient.jpg` | Sekcja "Dlaczego my?" |
| `accompaniment-abortion-process.jpg` | Blog (ostrożnie - wrażliwy temat) |

---

## 5. Bannery / Marketing

| Plik | Przeznaczenie |
|------|---------------|
| `baner_ulekarza.png` | Banner promocyjny |
| `sgoz_miniatur_szablon.png` | Szablon miniatur |
| `153539.png` | Dodatkowy banner |

---

## 6. Małe ikony

| Plik | Przeznaczenie |
|------|---------------|
| `inonka_bakteria.png` | Ikona - mikrobiologia |
| `inonka_sluchawki_lekarskie.png` | Ikona - stetoskop |

---

## 7. Stockowe zdjęcia (do rozważenia)

| Plik | Opis |
|------|------|
| `2148239052.jpg` | Stock photo |
| `2148998309.jpg` | Stock photo |
| `2149335672.jpg` | Stock photo |
| `35291.jpg` | Stock photo |
| `60616.jpg` | Stock photo |

---

## Struktura w projekcie Laravel

Po inicjalizacji Laravel, pliki trafią do:

```
public/
├── images/
│   ├── hero/
│   │   └── beautiful-female-therapist-clinic.jpg
│   ├── services/
│   │   ├── lekarz_rodzinny.png
│   │   ├── laboratorium.png
│   │   ├── stopmatolog_uslugi.png
│   │   └── ...
│   ├── doctors/
│   │   ├── anna-kowalska.png
│   │   ├── maria-wisniewska.png
│   │   └── ...
│   ├── icons/
│   │   ├── bakteria.png
│   │   └── stetoskop.png
│   └── blog/
│       └── ...
```

---

## Optymalizacja (TODO po setup)

1. **Konwersja do WebP** - zmniejszenie rozmiaru o ~30%
2. **Responsive images** - srcset dla różnych rozmiarów
3. **Lazy loading** - dla obrazów poniżej fold
4. **Blur placeholder** - dla lepszego UX ładowania

```bash
# Przykład konwersji (po instalacji sharp/imagemagick)
npx sharp-cli --input media/*.jpg --output public/images/ --format webp --quality 80
```

---

*Grafiki są gotowe do użycia. Brakuje tylko zdjęć męskich lekarzy.*
