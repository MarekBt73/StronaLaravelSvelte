# Project Context: Medical Clinic Platform (Laravel + Svelte)

## Dokumentacja projektu

Lista wszystkich instrukcji i dokumentow w projekcie:

| Plik | Opis |
|------|------|
| [CLAUDE.md](CLAUDE.md) | Glowne instrukcje dla AI - architektura, standardy, wytyczne |
| [TASK_PLAN.md](TASK_PLAN.md) | Plan zadan i postep prac (~75% ukonczony) |
| [PLAN_PROJEKTU.md](PLAN_PROJEKTU.md) | Ogolny plan projektu |
| [DEPLOY_DHOSTING.md](DEPLOY_DHOSTING.md) | Instrukcja deploymentu na dhosting.pl |
| [BRANDING.md](BRANDING.md) | Wytyczne brandingowe (kolory, logo, styl) |
| [ASSETS.md](ASSETS.md) | Lista zasobow graficznych |
| [README.md](README.md) | Opis projektu Laravel |
| [docs/CONTENT_GEO_GUIDELINES.md](docs/CONTENT_GEO_GUIDELINES.md) | Wytyczne SEO/GEO dla tresci blogowych |

**WAZNE:** Przed rozpoczeciem pracy zapoznaj sie z odpowiednimi dokumentami powyzej.

## 1. Project Overview
- **Type:** Medical Clinic Management System & SEO-Optimized Marketing Site.
- **Status:** Strona prezentacyjna/demo (fikcyjne dane, popup informacyjny, robots.txt blokuje indeksowanie)
- **Stack:**
  - **Backend:** Laravel 11.x (PHP 8.2+)
  - **Frontend:** Svelte 5 (via Inertia.js)
  - **Admin Panel:** Filament PHP v3 + TinyMCE Editor
  - **Database:** MySQL (dhosting.pl) / PostgreSQL (Railway)
  - **Styling:** Tailwind CSS
  - **Infrastructure:** dhosting.pl (produkcja)
  - **Production URL:** https://medvita.becht.pl/laravel
  - **Image Processing:** Intervention/Image (WebP, responsive sizes)
  - **Global Search:** SearchController + modal w MainLayout (artykuly + strony statyczne)

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

## 4. Accessibility (WCAG 2.1 AA) & Inclusive Design [CRITICAL]

### Core Rules
- **Semantic HTML First:** Use `<button>` for actions, `<a>` for navigation. Never use `<div on:click>` without `role="button"` and keyboard event handlers.
- **Keyboard Navigation:** Ensure ALL interactive elements are reachable via TAB key.
- **Focus States:** Never set `outline: none` without providing a custom visible focus style (use Tailwind `focus-visible:ring`).
- **Forms:** Every `<input>` must have a linked `<label>` (via `for` attribute or nesting). Placeholders are NOT labels.

### Component Implementation Guidelines
- **Images:**
  - Decorative images: `alt=""` (empty string).
  - Informative images: `alt="Description of content"`.
  - Dynamic images (from DB): Use `alt={doctor.name + ' - zdjęcie profilowe'}`.
- **Modals (Svelte):** Implement focus trapping. When modal opens, focus moves inside. When closed, focus returns to the trigger button.
- **Color Contrast:** Ensure text/background ratio is at least 4.5:1. Avoid relying on color alone to convey state (e.g., error fields must have text/icon, not just red border).

### AI Automation for A11y
- **Alt Text Fallback:** In Filament resources, if `alt_text` is empty, trigger an Async Job to generate it via OpenAI Vision API.
- **Video:** If video content is added, prioritize layouts that support transcripts/captions.

## 5. Performance & Mobile-First [CRITICAL]

### Image Optimization
- **Format:** ALL images must be WebP with fallback to original format.
- **Responsive:** Generate 4 sizes: mobile (480px), tablet (768px), desktop (1200px), original (max 1920px).
- **Lazy Loading:** Use `loading="lazy"` for images below the fold.
- **Srcset:** Always provide srcset and sizes attributes:
```svelte
<img
  src="/images/hero/doctor-desktop.webp"
  srcset="/images/hero/doctor-mobile.webp 480w,
          /images/hero/doctor-tablet.webp 768w,
          /images/hero/doctor-desktop.webp 1200w"
  sizes="(max-width: 480px) 480px, (max-width: 768px) 768px, 1200px"
  alt="Lekarz w klinice MedVita"
  loading="lazy"
/>
```

### Mobile-First CSS
- **Breakpoints (Tailwind):** Start with mobile, then add `md:` and `lg:` modifiers.
- **Touch Targets:** Minimum 44x44px for all interactive elements.
- **Font Sizes:** Base 16px, never smaller than 14px on mobile.

### Core Web Vitals Targets
| Metric | Target | Priority |
|--------|--------|----------|
| LCP (Largest Contentful Paint) | < 2.5s | P0 |
| FID (First Input Delay) | < 100ms | P0 |
| CLS (Cumulative Layout Shift) | < 0.1 | P0 |

### Performance Rules
- **No layout shifts:** Always set width/height or aspect-ratio on images.
- **Critical CSS:** Inline above-the-fold styles.
- **Fonts:** Use `font-display: swap` for Google Fonts.
- **Bundle size:** Monitor with `npm run build -- --analyze`.

## 6. Security & Privacy (GDPR/RODO)
- **PII Data:** Never log patient data (PESEL, symptoms, names) in application logs.
- **Authorization:** Use Laravel Policies strictly. A user can only see their own appointments.
- **Files:** All medical uploads must go to S3 (Private Bucket), never `public` disk.

## 7. Key Commands
- `php artisan serve` - Start backend.
- `npm run dev` - Start Vite frontend watcher.
- `php artisan filament:make-user` - Create admin user.
- `php artisan inertia:start-ssr` - Debug SSR locally.

## 8. Implementation roadmap (Current Focus)
- Setup Laravel with Inertia & Svelte adapter.
- Install Filament v3 for "Doctors" and "Appointments" CRUD.
- Implement Booking Flow (Step 1: Specialist, Step 2: Date, Step 3: Auth).

## Comunication
Komunikuj się wyłącznie w języku polskim.