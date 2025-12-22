# Plan Projektu: Klinika Medyczna

## 1. Architektura Systemu

```
┌─────────────────────────────────────────────────────────────┐
│                        FRONTEND                              │
│  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────┐ │
│  │  Strona Publ.   │  │  Panel Pacjenta │  │ Panel Admin  │ │
│  │  (Svelte+SSR)   │  │  (Svelte SPA)   │  │ (Filament)   │ │
│  │  - Home         │  │  - Rezerwacje   │  │ - Lekarze    │ │
│  │  - Usługi       │  │  - Historia     │  │ - Grafiki    │ │
│  │  - Lekarze      │  │  - Profil       │  │ - Pacjenci   │ │
│  │  - Blog         │  │                 │  │ - Raporty    │ │
│  │  - Kontakt      │  │                 │  │              │ │
│  └─────────────────┘  └─────────────────┘  └──────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     LARAVEL 11 + INERTIA                     │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌─────────────┐  │
│  │Controllers│  │ Services │  │ Policies │  │FormRequests │  │
│  └──────────┘  └──────────┘  └──────────┘  └─────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      POSTGRESQL                              │
│        (Supabase / Railway / lokalne dev)                   │
└─────────────────────────────────────────────────────────────┘
```

## 2. Struktura Folderów Laravel

```
StronaLaravelSvelte/
├── app/
│   ├── Actions/              # Pojedyncze akcje biznesowe
│   │   └── Booking/
│   │       ├── CreateAppointment.php
│   │       └── CancelAppointment.php
│   ├── Filament/             # Panel admina
│   │   ├── Resources/
│   │   │   ├── DoctorResource.php
│   │   │   ├── ServiceResource.php
│   │   │   ├── AppointmentResource.php
│   │   │   └── PatientResource.php
│   │   └── Widgets/
│   │       └── StatsOverview.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PageController.php        # Strony statyczne
│   │   │   ├── DoctorController.php      # Lista lekarzy
│   │   │   ├── ServiceController.php     # Usługi
│   │   │   ├── BookingController.php     # Rezerwacje
│   │   │   └── BlogController.php        # Blog
│   │   └── Requests/
│   │       └── BookingRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Doctor.php
│   │   ├── Service.php
│   │   ├── Appointment.php
│   │   ├── Schedule.php        # Grafik lekarza
│   │   ├── BlogPost.php
│   │   └── ContactMessage.php
│   ├── Policies/
│   │   └── AppointmentPolicy.php
│   └── Services/
│       ├── SeoService.php      # Schema.org generator
│       └── ScheduleService.php # Logika dostępności
│
├── resources/
│   ├── js/
│   │   ├── app.js
│   │   ├── ssr.js
│   │   ├── Pages/              # Strony Svelte
│   │   │   ├── Home.svelte
│   │   │   ├── Services/
│   │   │   │   ├── Index.svelte
│   │   │   │   └── Show.svelte
│   │   │   ├── Doctors/
│   │   │   │   ├── Index.svelte
│   │   │   │   └── Show.svelte
│   │   │   ├── Booking/
│   │   │   │   ├── Step1Service.svelte
│   │   │   │   ├── Step2DateTime.svelte
│   │   │   │   ├── Step3Confirm.svelte
│   │   │   │   └── Success.svelte
│   │   │   ├── Blog/
│   │   │   │   ├── Index.svelte
│   │   │   │   └── Show.svelte
│   │   │   ├── Patient/        # Panel pacjenta
│   │   │   │   ├── Dashboard.svelte
│   │   │   │   └── Appointments.svelte
│   │   │   └── Auth/
│   │   │       ├── Login.svelte
│   │   │       └── Register.svelte
│   │   ├── Components/
│   │   │   ├── Layout/
│   │   │   │   ├── Navbar.svelte
│   │   │   │   ├── Footer.svelte
│   │   │   │   └── MainLayout.svelte
│   │   │   ├── Booking/
│   │   │   │   ├── Calendar.svelte
│   │   │   │   └── TimeSlots.svelte
│   │   │   ├── Seo/
│   │   │   │   └── JsonLd.svelte
│   │   │   └── UI/
│   │   │       ├── Button.svelte
│   │   │       ├── Card.svelte
│   │   │       └── Modal.svelte
│   │   └── Stores/
│   │       └── booking.js      # Stan rezerwacji
│   │
│   └── views/
│       └── app.blade.php       # Główny template
│
├── routes/
│   └── web.php
│
├── database/
│   └── migrations/
│
├── tests/
│   └── Feature/
│
└── docker/                     # Konfiguracja Railway
    └── Dockerfile
```

## 3. Modele Danych (ERD)

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    users     │       │   doctors    │       │   services   │
├──────────────┤       ├──────────────┤       ├──────────────┤
│ id           │       │ id           │       │ id           │
│ name         │       │ user_id (FK) │       │ name         │
│ email        │       │ specialization│      │ slug         │
│ phone        │       │ title        │       │ description  │
│ password     │       │ bio          │       │ duration_min │
│ role         │       │ photo        │       │ price        │
│ created_at   │       │ is_active    │       │ is_active    │
└──────────────┘       └──────────────┘       └──────────────┘
       │                      │                      │
       │                      │                      │
       ▼                      ▼                      │
┌──────────────────────────────────────────────────────────────┐
│                        appointments                           │
├──────────────────────────────────────────────────────────────┤
│ id | patient_id (FK) | doctor_id (FK) | service_id (FK)      │
│ scheduled_at | status (pending/confirmed/cancelled/completed)│
│ notes | created_at                                           │
└──────────────────────────────────────────────────────────────┘

┌──────────────┐       ┌──────────────┐
│  schedules   │       │  blog_posts  │
├──────────────┤       ├──────────────┤
│ id           │       │ id           │
│ doctor_id    │       │ title        │
│ day_of_week  │       │ slug         │
│ start_time   │       │ content      │
│ end_time     │       │ excerpt      │
│ is_available │       │ featured_img │
└──────────────┘       │ author_id    │
                       │ published_at │
                       └──────────────┘
```

## 4. Fazy Implementacji

### Faza 1: Fundament (Tydzień startowy)
- [ ] Inicjalizacja Laravel 11
- [ ] Konfiguracja Inertia.js + Svelte
- [ ] Instalacja Tailwind CSS
- [ ] Instalacja Filament v3
- [ ] Konfiguracja PostgreSQL (lokalne dev)
- [ ] Podstawowy layout (Navbar, Footer)
- [ ] Pierwsza strona Home

### Faza 2: Panel Admina (Filament)
- [ ] Model Doctor + Resource
- [ ] Model Service + Resource
- [ ] Model Schedule + Resource
- [ ] Model Appointment + Resource
- [ ] Dashboard z statystykami

### Faza 3: Strona Publiczna
- [ ] Strona główna (Hero, Usługi, Lekarze, CTA)
- [ ] Lista usług + szczegóły
- [ ] Lista lekarzy + profile
- [ ] Strona kontaktowa z formularzem
- [ ] Schema.org dla wszystkich stron

### Faza 4: System Rezerwacji
- [ ] Flow rezerwacji (3 kroki)
- [ ] Kalendarz dostępności
- [ ] Rejestracja/logowanie pacjenta
- [ ] Potwierdzenie email
- [ ] Panel pacjenta (moje wizyty)

### Faza 5: Blog & SEO
- [ ] CRUD blogpostów (Filament)
- [ ] Lista artykułów + widok pojedynczy
- [ ] Sitemap.xml
- [ ] Robots.txt
- [ ] Meta tagi dynamiczne

### Faza 6: Deployment
- [ ] Dockerfile
- [ ] Konfiguracja Railway
- [ ] Podpięcie domeny
- [ ] SSL

## 5. Stack Technologiczny - Szczegóły

| Warstwa | Technologia | Wersja |
|---------|-------------|--------|
| Backend | Laravel | 11.x |
| Frontend | Svelte | 5.x |
| Bridge | Inertia.js | 1.x |
| Admin | Filament | 3.x |
| CSS | Tailwind | 3.x |
| DB | PostgreSQL | 15+ |
| Cache | Redis | opcjonalnie |
| Deploy | Railway | Docker |

## 6. Komendy Startowe

```bash
# 1. Tworzenie projektu Laravel
composer create-project laravel/laravel . "11.*"

# 2. Instalacja Inertia (server-side)
composer require inertiajs/inertia-laravel

# 3. Instalacja Svelte adapter
npm install @inertiajs/svelte svelte @sveltejs/vite-plugin-svelte

# 4. Instalacja Filament
composer require filament/filament:"^3.0"
php artisan filament:install --panels

# 5. Tailwind
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

## 7. Pytania do Ustalenia

Zanim przejdziemy dalej, potrzebuję Twoich decyzji:

1. **Nazwa kliniki** - potrzebna do SEO i brandingu
2. **Specjalizacje** - jakie usługi/specjalizacje medyczne?
3. **Kolorystyka** - masz preferencje? (medyczne: niebieski/zielony/biały)
4. **Płatności online** - czy integrować? (Przelewy24/Stripe)
5. **Powiadomienia** - SMS czy tylko email?
6. **Wielojęzyczność** - tylko PL czy też EN?

---

*Gdy znajdziesz inspiracje do wyglądu, podziel się linkami - dostosuję plan wizualny.*
