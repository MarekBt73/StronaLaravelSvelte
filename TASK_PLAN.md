# Plan zadań - MedVita

## Status: W trakcie realizacji
**Data utworzenia:** 22.12.2024

---

## PRIORYTET 1: System ról użytkowników (rozpoczęty)

### 1.1 Dokończyć model User
- [ ] Dodać stałe ról: `admin`, `lekarz`, `asystent`, `redaktor`, `technik`
- [ ] Dodać metody pomocnicze: `isAdmin()`, `isDoctor()`, `isEditor()`, etc.
- [ ] Dodać scope'y: `scopeActive()`, `scopeByRole()`

### 1.2 Utworzyć UserResource w Filament
- [ ] Formularz tworzenia/edycji użytkownika
- [ ] Lista użytkowników z filtrami (rola, status)
- [ ] Walidacja: tylko admin może zarządzać użytkownikami
- [ ] Upload avatara

### 1.3 Polityki dostępu (Policies)
- [ ] `UserPolicy` - tylko admin może CRUD użytkowników
- [ ] `ArticlePolicy` - redaktor, admin, technik
- [ ] `CategoryPolicy` - redaktor, admin, technik

### 1.4 Konfiguracja nawigacji Filament
- [ ] Ukryć menu "Użytkownicy" dla nie-adminów
- [ ] Ukryć menu "Blog" dla lekarzy/asystentów
- [ ] Dodać grupę "Wizyty" dla lekarzy/asystentów

---

## PRIORYTET 2: System wizyt/spotkań

### 2.1 Migracje
- [ ] `appointments` - wizyty pacjentów
- [ ] `doctors` - profil lekarza (specjalizacja, godziny pracy)
- [ ] `schedules` - grafik dostępności lekarzy
- [ ] `patients` - dane pacjentów (opcjonalnie)

### 2.2 Modele Eloquent
- [ ] `Doctor` - rozszerzenie User lub osobny model
- [ ] `Appointment` - rezerwacja wizyty
- [ ] `Schedule` - sloty czasowe

### 2.3 Filament Resources
- [ ] `AppointmentResource` - zarządzanie wizytami
- [ ] `DoctorResource` - profil lekarza
- [ ] `ScheduleResource` - grafik pracy

---

## PRIORYTET 3: Frontend - strona bloga

### 3.1 Strona listy artykułów `/blog`
- [ ] Kontroler `BlogController`
- [ ] Widok Svelte `Blog.svelte`
- [ ] Filtrowanie po kategorii
- [ ] Paginacja

### 3.2 Strona pojedynczego artykułu `/blog/{slug}`
- [ ] Widok `BlogPost.svelte`
- [ ] Schema.org JSON-LD dla artykułu
- [ ] Dynamiczne meta tagi SEO
- [ ] Licznik wyświetleń

---

## PRIORYTET 4: Integracja aktualności na stronie głównej

### 4.1 Dynamiczne dane
- [ ] Kontroler pobierający ostatnie 3 artykuły
- [ ] Przekazywanie przez Inertia props
- [ ] Fallback gdy brak artykułów

---

## PRIORYTET 5: Rezerwacja online (Booking Flow)

### 5.1 Krok 1: Wybór specjalisty
- [ ] Lista dostępnych lekarzy
- [ ] Filtrowanie po specjalizacji

### 5.2 Krok 2: Wybór terminu
- [ ] Kalendarz dostępności
- [ ] Wyświetlanie wolnych slotów

### 5.3 Krok 3: Autoryzacja/dane
- [ ] Logowanie/rejestracja pacjenta
- [ ] Formularz danych kontaktowych
- [ ] Potwierdzenie rezerwacji

---

## Notatki techniczne

### Dane logowania do panelu admin
- **URL:** http://127.0.0.1:8000/admin
- **Email:** admin@medvita.pl
- **Hasło:** admin123

### Role użytkowników
| Rola | Uprawnienia |
|------|-------------|
| `admin` | Pełny dostęp |
| `technik` | Jak admin, ale bez zarządzania userami |
| `redaktor` | Tylko artykuły i kategorie |
| `lekarz` | Wizyty, terminy, grafik |
| `asystent` | Wizyty, terminy, grafik |

### Komendy
```bash
# Uruchomienie serwera
php artisan serve

# Uruchomienie Vite
npm run dev

# Build produkcyjny
npm run build

# Tworzenie nowego użytkownika Filament
php artisan filament:make-user
```

---

## Ukończone zadania (22.12.2024)

- [x] Instalacja Filament v3.3.45
- [x] Migracje: categories, articles
- [x] Modele: Category, Article
- [x] Filament Resources: CategoryResource, ArticleResource
- [x] Migracja: dodanie pola `role` do users
- [x] Sekcja Aktualności na stronie głównej
- [x] Sekcja Mapa lokalizacji na stronie głównej
- [x] Kopiowanie grafik do public/images
- [x] Integracja grafik na wszystkich stronach
- [x] Fix grid mobilny (1 kolumna)
- [x] Utworzenie superusera
