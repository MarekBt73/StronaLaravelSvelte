# Project Context: Medical Clinic Platform (Laravel + Svelte)

## 1. Project Overview
- **Type:** Medical Clinic Management System & SEO-Optimized Marketing Site.
- **Stack:** - **Backend:** Laravel 11.x (PHP 8.2+).
  - **Frontend:** Svelte 4/5 (via Inertia.js).
  - **Admin Panel:** Filament PHP v3.
  - **Database:** PostgreSQL (Supabase/Railway).
  - **Styling:** Tailwind CSS.
  - **Infrastructure:** Railway (Dockerized).

## 2. Architecture & Patterns

### Core Principles
- **Inertia.js Monolith:** We are building a modern monolith. Do not build a separate REST API unless explicitly requested for 3rd party integrations.
- **Filament First:** All administrative tasks (Doctors, Reception, Content Management) must use Filament Resources. Do not build custom admin views in Svelte.
- **Islands Architecture (Logical):** - Marketing pages (Blog, Home) -> Server Side Rendered (SSR) for SEO.
  - Patient Panel/Booking -> Client Side Interactivity.

### Coding Standards (PHP/Laravel)
- **Strict Typing:** Always use `declare(strict_types=1);` and return types.
- **Controllers:** Keep them thin. Move business logic to `app/Services` or `app/Actions`.
- **Models:** Use `$guarded = []` (unguarded) but validate strictly in FormRequests.
- **Routes:** Use `web.php` for everything served via Inertia.
- **Testing:** Prioritize Feature tests (PestPHP) over Unit tests.

### Coding Standards (Svelte/Frontend)
- **Composition:** Use `<script setup>` logic (Svelte 5 runes or Svelte 4 stores).
- **Styling:** Use Tailwind utility classes. Avoid `<style>` blocks unless necessary for complex animations.
- **Links:** Always use the `Link` component from `@inertiajs/svelte` instead of `<a>` tags for internal navigation.
- **State:** Use `usePage()` props for shared data (User, Flash messages).

## 3. SEO & AIO (Artificial Intelligence Optimization) Guidelines
*Critical for this project.*

- **Schema.org:** Every public page MUST include JSON-LD structured data:
  - `MedicalBusiness` for the homepage.
  - `MedicalWebPage` for services.
  - `Physician` for doctor profiles.
  - `FAQPage` for Q&A sections (optimized for Voice Search/LLMs).
- **Semantic HTML:** Use `<article>`, `<section>`, `<header>`, `<nav>` strictly.
- **Meta Tags:** Dynamic generation via `seo-tags` package or custom helper.
- **Content Structure:** Use clear "Key Takeaways" bullet points at the start of blog posts (LLM friendly).

## 4. Security & Privacy (GDPR/RODO)
- **PII Data:** Never log patient data (PESEL, symptoms, names) in application logs.
- **Authorization:** Use Laravel Policies strictly. A user can only see their own appointments.
- **Files:** All medical uploads must go to S3 (Private Bucket), never `public` disk.

## 5. Key Commands
- `php artisan serve` - Start backend.
- `npm run dev` - Start Vite frontend watcher.
- `php artisan filament:make-user` - Create admin user.
- `php artisan inertia:start-ssr` - Debug SSR locally.

## 6. Implementation roadmap (Current Focus)
- Setup Laravel with Inertia & Svelte adapter.
- Install Filament v3 for "Doctors" and "Appointments" CRUD.
- Implement Booking Flow (Step 1: Specialist, Step 2: Date, Step 3: Auth).