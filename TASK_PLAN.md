# Plan zadań - MedVita

## Status projektu
**Data aktualizacji:** 23.12.2024
**Postęp ogólny:** ~50% ukończone

```
████████████░░░░░░░░░░░░ 50%
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
- [x] ArticleResource (CRUD artykułów z rich editor)

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
- [ ] Dodać grupę "Wizyty" (widoczna dla lekarzy/asystentów) → przeniesione do FAZA 4

---

## FAZA 4: System wizyt ⏳ NASTĘPNY ETAP

### 4.1 Migracje
- [ ] `doctors` - profil lekarza (specjalizacja, bio, godziny)
- [ ] `schedules` - grafik dostępności
- [ ] `appointments` - rezerwacje wizyt
- [ ] `patients` - dane pacjentów (opcjonalnie)

### 4.2 Modele
- [ ] Doctor (rozszerzenie User lub relacja)
- [ ] Schedule (sloty czasowe)
- [ ] Appointment (rezerwacja)

### 4.3 Filament Resources
- [ ] DoctorResource
- [ ] ScheduleResource
- [ ] AppointmentResource
- [ ] Grupa nawigacji "Wizyty" (widoczna dla lekarzy/asystentów)

---

## FAZA 5: Frontend bloga 📋 ZAPLANOWANA

### 5.1 Strona listy `/blog`
- [ ] BlogController@index
- [ ] Blog.svelte (lista artykułów)
- [ ] Filtrowanie po kategorii
- [ ] Paginacja

### 5.2 Strona artykułu `/blog/{slug}`
- [ ] BlogController@show
- [ ] BlogPost.svelte
- [ ] Schema.org JSON-LD
- [ ] Dynamiczne meta SEO
- [ ] Licznik wyświetleń

### 5.3 Integracja z homepage
- [ ] Dynamiczne pobieranie ostatnich 3 artykułów
- [ ] Przekazywanie przez Inertia props

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

## Tabela ról

| Rola | Użytkownicy | Artykuły | Kategorie | Wizyty | Grafik |
|------|:-----------:|:--------:|:---------:|:------:|:------:|
| admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| technik | ❌ | ✅ | ✅ | ✅ | ✅ |
| redaktor | ❌ | ✅ | ✅ | ❌ | ❌ |
| lekarz | ❌ | ❌ | ❌ | ✅ | ✅ |
| asystent | ❌ | ❌ | ❌ | ✅ | ✅ |

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
| Panel admin | http://127.0.0.1:8000/admin | admin@medvita.pl | admin123 |
| Strona główna | http://127.0.0.1:8000 | - | - |

---

**Legenda:**
- ✅ Ukończone
- 🔄 W trakcie
- ⏳ Następny krok
- 📋 Zaplanowane
- ❌ Brak dostępu
