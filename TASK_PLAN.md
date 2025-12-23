# Plan zadań - MedVita

## Status projektu
**Data aktualizacji:** 23.12.2024
**Postęp ogólny:** ~70% ukończone
**Produkcja:** https://medvita.becht.pl/laravel

```
████████████████░░░░░░░░ 70%
```

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

### 2.2 System blogowy ✅
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
- [ ] Dodać grupę "Wizyty" (widoczna dla lekarzy/asystentów) → przeniesione do FAZA 5

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

---

## FAZA 5: System wizyt ⏳ NASTĘPNY ETAP

### 5.1 Migracje
- [ ] `doctors` - profil lekarza (specjalizacja, bio, godziny)
- [ ] `schedules` - grafik dostępności
- [ ] `appointments` - rezerwacje wizyt
- [ ] `patients` - dane pacjentów (opcjonalnie)

### 5.2 Modele
- [ ] Doctor (rozszerzenie User lub relacja)
- [ ] Schedule (sloty czasowe)
- [ ] Appointment (rezerwacja)

### 5.3 Filament Resources
- [ ] DoctorResource
- [ ] ScheduleResource
- [ ] AppointmentResource
- [ ] Grupa nawigacji "Wizyty" (widoczna dla lekarzy/asystentów)

---

## FAZA 6: Booking Flow 📋 ZAPLANOWANA

### 6.1 Krok 1: Wybór specjalisty
- [ ] Lista lekarzy z filtrami
- [ ] Karty lekarzy ze specjalizacjami

### 6.2 Krok 2: Wybór terminu
- [ ] Kalendarz dostępności
- [ ] Wyświetlanie wolnych slotów

### 6.3 Krok 3: Potwierdzenie
- [ ] Logowanie/rejestracja pacjenta
- [ ] Formularz danych
- [ ] Email z potwierdzeniem

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

| Rola | Użytkownicy | Artykuły | Kategorie | Wizyty | Grafik |
|------|:-----------:|:--------:|:---------:|:------:|:------:|
| admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| technik | ❌ | ✅ | ✅ | ✅ | ✅ |
| redaktor | ❌ | ✅ | ✅ | ❌ | ❌ |
| lekarz | ❌ | ❌ | ❌ | ✅ | ✅ |
| asystent | ❌ | ❌ | ❌ | ✅ | ✅ |

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
