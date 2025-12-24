# Plan zadań - MedVita

## Status projektu
**Data aktualizacji:** 24.12.2024 (wieczór)
**Postęp ogólny:** ~85% ukończone
**Produkcja:** https://medvita.becht.pl/laravel
**Uwaga:** Strona prezentacyjna z fikcyjnymi danymi (popup informacyjny + blokada robotów)

```
█████████████████████░░░ 85%
```

### Priorytety na 25.12.2024:
1. **FAZA 5** - Formularz kontaktowy z Filament
2. **FAZA 6** - Edycja stron statycznych (jeśli czas pozwoli)

---

## FAZA 1: Fundament ✅ UKOŃCZONA

### 1.1 Setup projektu ✅
- [x] Laravel 11 + Inertia.js + Svelte 5
- [x] Tailwind CSS z custom paletą "medical"
- [x] Struktura folderów i routing
- [x] MainLayout.svelte

### 1.2 Strony statyczne ✅
- [x] Home.svelte (strona główna)
- [x] About.svelte (o nas)
- [x] Services.svelte (usługi)
- [x] Doctors.svelte (lekarze)
- [x] Contact.svelte (kontakt)
- [x] Legal/ (regulamin, prywatność, RODO)

### 1.3 Grafiki i zasoby ✅
- [x] Import grafik z media/ do public/images/
- [x] Integracja zdjęć na wszystkich stronach
- [x] Responsywny grid (mobile 1 kolumna)

---

## FAZA 2: Panel administracyjny ✅ UKOŃCZONA

### 2.1 Filament v3 ✅
- [x] Instalacja Filament v3.3.45
- [x] AdminPanelProvider
- [x] Superuser: admin@medvita.pl / admin123

### 2.2 Dashboard widgety ✅ (24.12.2024)
- [x] StatsOverviewWidget (artykuły, media, kategorie)
- [x] LatestArticlesWidget (tabela 5 ostatnich artykułów)
- [x] QuickLinksWidget (linki: strona, poczta, dokumentacja, media)
- [x] ArticlesChartWidget (wykres artykułów 30 dni)
- [x] SystemInfoWidget (PHP, Laravel, Filament, storage, debug)

### 2.3 System blogowy ✅
- [x] Migracja: categories (typ: news/medical)
- [x] Migracja: articles (SEO, featured image)
- [x] Model Category + scopes
- [x] Model Article + scopes
- [x] CategoryResource (CRUD kategorii)
- [x] ArticleResource (CRUD artykułów z TinyMCE)
- [x] TinyMCE Editor (pełna wersja - obrazy, filmy, HTML)

### 2.3 Sekcje na stronie głównej ✅
- [x] Sekcja "Aktualności" (3 karty artykułów)
- [x] Sekcja "Nasze placówki" (3 lokalizacje + mapa)

---

## FAZA 3: System ról ✅ UKOŃCZONA

### 3.1 Migracja ról ✅
- [x] Pole `role` w tabeli users
- [x] Pole `is_active` w tabeli users
- [x] Pole `phone`, `avatar`

### 3.2 Model User ✅
- [x] Stałe ról: ROLE_ADMIN, ROLE_DOCTOR, etc.
- [x] Metody: isAdmin(), isDoctor(), isEditor(), isTechnician(), isAssistant()
- [x] Metody uprawnień: canManageUsers(), canManageBlog(), canManageAppointments()
- [x] Scopes: scopeActive(), scopeByRole(), scopeAdmins(), scopeDoctors(), scopeEditors()
- [x] Implementacja FilamentUser interface

### 3.3 UserResource w Filament ✅
- [x] Formularz CRUD użytkowników
- [x] Walidacja: tylko admin zarządza userami (canAccess)
- [x] Lista z filtrami (rola, status aktywności)
- [x] Kolorowe badge dla ról
- [x] Placeholder dla avatara (ui-avatars.com)

### 3.4 Polityki dostępu ✅
- [x] UserPolicy - tylko admin
- [x] ArticlePolicy - redaktor, admin, technik
- [x] CategoryPolicy - redaktor, admin, technik

### 3.5 Nawigacja Filament wg ról ✅
- [x] Ukryć "Użytkownicy" dla nie-adminów
- [x] Ukryć "Blog" dla lekarzy/asystentów

---

## FAZA 4: Frontend bloga ✅ UKOŃCZONA

### 4.1 Strona listy `/blog` ✅
- [x] BlogController@index
- [x] Blog/Index.svelte (lista artykułów)
- [x] Filtrowanie po kategorii
- [x] Paginacja
- [x] Wyszukiwarka artykułów
- [x] Sidebar z kategoriami

### 4.2 Strona artykułu `/blog/{slug}` ✅
- [x] BlogController@show
- [x] Blog/Show.svelte
- [x] Licznik wyświetleń
- [x] Powiązane artykuły
- [x] Social sharing (Facebook, X, LinkedIn)

### 4.3 SEO i Meta tagi ✅
- [x] Open Graph meta tagi
- [x] Twitter Card meta tagi
- [x] Dynamiczne meta SEO
- [x] Udostępnianie z grafiką

### 4.4 Optymalizacja obrazów ✅
- [x] Intervention/Image zainstalowany
- [x] ImageService (WebP, responsive sizes)
- [x] ArticleObserver (auto-przetwarzanie obrazów)
- [x] ResponsiveImage.svelte component

### 4.5 Integracja z homepage ✅
- [x] Dynamiczne pobieranie ostatnich 3 artykułów
- [x] Przekazywanie przez Inertia props

### 4.6 Usprawnienia UX (24.12.2024) ✅
- [x] Hero bloga z tłem graficznym (jak na stronie głównej)
- [x] Rozwijana wyszukiwarka w hero bloga (ikona lupy)
- [x] Globalna wyszukiwarka w menu (lupa → modal)
- [x] SearchController - wyszukiwanie artykułów + stron statycznych
- [x] Weryfikacja email w panelu (przyciski: wyślij/zweryfikuj ręcznie)
- [x] Status weryfikacji email w tabeli użytkowników

### 4.7 Strona prezentacyjna (24.12.2024) ✅
- [x] Popup informacyjny po 10 sekundach
- [x] Link do becht.pl (zamów stronę)
- [x] Zapamiętywanie zamknięcia w localStorage
- [x] robots.txt blokujący wszystkie roboty

### 4.8 Biblioteka mediów (24.12.2024) ✅
- [x] Model Media z wariantami responsive (thumbnail, mobile, tablet, desktop)
- [x] MediaService - upload z kompresją WebP
- [x] MediaResource w Filament (CRUD, podgląd, bulk upload)
- [x] AI generowanie ALT i tagów (Gemini Vision API)
- [x] Integracja z ArticleResource (wybór z galerii lub upload)
- [x] Limity upload: 1GB, video MP4/WebM/MOV/AVI/MKV

### 4.9 Dostępność (24.12.2024) ✅
- [x] AccessibilityPanel.svelte (rozmiar czcionki, kontrast, wielkie litery, motyw)
- [x] Style CSS dla trybów dostępności
- [x] Zapis ustawień w localStorage
- [x] Przycisk dostępności w rogu strony

### 4.10 Statystyki wyświetleń (24.12.2024) ✅
- [x] Migracja: `article_views` (dzienne statystyki)
- [x] Migracja: `article_visitor_sessions` (unikalni użytkownicy - hash sesji)
- [x] Model ArticleView z metodą `recordView()` i `getChartData()`
- [x] Model ArticleVisitorSession (RODO - bez IP)
- [x] BlogController zapisuje dzienne wyświetlenia
- [x] LatestArticlesWidget - wykres z prawdziwymi danymi
- [x] Popup statystyk z wykresem (wyświetlenia + unikalni)

### 4.11 Cookie Consent (24.12.2024) ✅
- [x] CookieConsent.svelte - popup zgodny z RODO/GDPR
- [x] Kategorie: niezbędne, analityczne, marketingowe
- [x] Przyciski: Akceptuj wszystkie / Tylko niezbędne / Dostosuj
- [x] Szczegółowy widok z przełącznikami
- [x] Zapis preferencji w localStorage
- [x] Link "Ustawienia cookies" w stopce
- [x] Event `cookieConsentChanged` dla skryptów zewnętrznych

### 4.12 Optymalizacja wydajności (24.12.2024) ✅
- [x] Zmiana koloru medical-600 na #0369a1 (WCAG AA contrast)
- [x] Komenda `php artisan images:optimize` (WebP + responsive)
- [x] Fix infinite loop w Svelte ($effect → onMount)
- [x] HTTPS force scheme dla produkcji

---

## FAZA 5: Formularz kontaktowy ⏳ NASTĘPNY ETAP (25.12.2024)

### 5.1 Model i migracja
- [ ] Model `Contact` (name, email, phone, subject, message, is_read, read_at)
- [ ] Migracja `contacts`
- [ ] Walidacja danych (FormRequest)

### 5.2 Frontend Svelte
- [ ] Rozbudowa Contact.svelte o działający formularz
- [ ] Walidacja po stronie klienta
- [ ] Stan wysyłania (loading, success, error)
- [ ] Honeypot antyspamowy
- [ ] Rate limiting (max 3 wiadomości / godzinę)

### 5.3 Panel Filament
- [ ] ContactResource (lista wiadomości)
- [ ] Filtrowanie: przeczytane/nieprzeczytane
- [ ] Oznaczanie jako przeczytane
- [ ] Bulk actions: oznacz przeczytane, usuń
- [ ] Badge w nawigacji (liczba nieprzeczytanych)

### 5.4 Powiadomienia email
- [ ] ContactNotification (Mailable)
- [ ] Wysyłka do administratora przy nowej wiadomości
- [ ] Konfiguracja odbiorcy w .env (CONTACT_EMAIL)
- [ ] Szablon email z danymi kontaktowymi

---

## FAZA 6: Edycja stron statycznych 📋 ZAPLANOWANA

### 6.1 Model i migracje
- [ ] `static_pages` - treści edytowalne (klucz, wartość JSON)
- [ ] `settings` - ustawienia globalne strony
- [ ] Seeder z domyślnymi wartościami

### 6.2 Filament Resources
- [ ] StaticPageResource - edycja treści stron
- [ ] SettingsResource - ustawienia globalne (logo, dane kontaktowe, social media)

### 6.3 Strony do edycji
- [ ] Strona główna (hero, sekcje, CTA)
- [ ] O nas (treść, zespół)
- [ ] Kontakt (adres, telefon, email, godziny)
- [ ] Usługi (lista usług, opisy)
- [ ] Regulamin, Polityka prywatności
- [ ] Stopka (linki, dane kontaktowe)

### 6.4 Funkcjonalności
- [ ] Edytor WYSIWYG (TinyMCE) dla treści
- [ ] Upload grafik do sekcji (integracja z MediaResource)
- [ ] Podgląd zmian przed zapisem (opcjonalnie)
- [ ] Cache treści statycznych

---

## Stack technologiczny

| Komponent | Technologia |
|-----------|-------------|
| Backend | Laravel 11.x (PHP 8.2+) |
| Frontend | Svelte 5 (via Inertia.js) |
| Admin Panel | Filament PHP v3 |
| Edytor treści | TinyMCE |
| Baza danych | MySQL (dhosting.pl) |
| Styling | Tailwind CSS |
| Hosting | dhosting.pl |
| Obrazy | Intervention/Image (WebP) |

---

## Tabela ról

| Rola | Użytkownicy | Artykuły | Kategorie | Kontakt | Media |
|------|:-----------:|:--------:|:---------:|:-------:|:-----:|
| admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| technik | ❌ | ✅ | ✅ | ✅ | ✅ |
| redaktor | ❌ | ✅ | ✅ | ❌ | ✅ |

---

## Dokumentacja

- [Content & GEO Guidelines](docs/CONTENT_GEO_GUIDELINES.md) - Wytyczne SEO/GEO dla treści blogowych

---

## Komendy deweloperskie

```bash
# Serwer Laravel
php artisan serve

# Vite (frontend)
npm run dev

# Build produkcyjny
npm run build

# Nowy użytkownik Filament
php artisan filament:make-user

# Migracje
php artisan migrate
```

---

## Dane dostępowe

| Zasób | URL | Login | Hasło |
|-------|-----|-------|-------|
| Produkcja | https://medvita.becht.pl/laravel | admin@medvita.pl | admin123 |
| Panel admin | .../admin | admin@medvita.pl | admin123 |
| Lokalnie | http://127.0.0.1:8000 | - | - |

---

**Legenda:**
- ✅ Ukończone
- 🔄 W trakcie
- ⏳ Następny krok
- 📋 Zaplanowane
- ❌ Brak dostępu
