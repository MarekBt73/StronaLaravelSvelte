# Deployment na dhosting.pl - Instrukcja krok po kroku

## Wymagania wstępne
- Konto na dhosting.pl z dostępem SSH
- Baza danych MySQL utworzona w panelu dhosting
- Domena skonfigurowana w dhosting

---

## KROK 1: Przygotowanie lokalne (na Twoim komputerze)

### 1.1 Zbuduj frontend
```bash
cd C:\Users\Dell\Documents\WoRK_MAREK_BECHT\AI_CODING_GMINI\StronaLaravelSvelte
npm run build
```

### 1.2 Wypchnij zmiany do Git
```bash
git add .
git commit -m "Build for dhosting deployment"
git push origin main
```

---

## KROK 2: Konfiguracja dhosting (w panelu)

### 2.1 Utwórz bazę danych MySQL
1. Zaloguj się do panelu dhosting
2. Przejdź do: **Bazy danych** → **MySQL**
3. Utwórz nową bazę danych
4. Zapisz dane:
   - Nazwa bazy: `__________`
   - Użytkownik: `__________`
   - Hasło: `__________`
   - Host: `localhost`

### 2.2 Skonfiguruj domenę
1. Przejdź do: **Domeny** → Twoja domena
2. Ustaw **katalog główny** na: `public_html/public`
   (lub później użyj .htaccess do przekierowania)

---

## KROK 3: Deployment przez SSH

### 3.1 Połącz się przez SSH
```bash
ssh twoj_login@twoj_serwer.dhosting.pl
```

### 3.2 Przejdź do katalogu public_html
```bash
cd ~/public_html
```

### 3.3 Sklonuj repozytorium
```bash
git clone https://github.com/MarekBt73/StronaLaravelSvelte.git .
```

**UWAGA**: Jeśli public_html nie jest pusty:
```bash
# Usuń istniejące pliki
rm -rf ~/public_html/*
rm -rf ~/public_html/.[!.]*

# Sklonuj
git clone https://github.com/MarekBt73/StronaLaravelSvelte.git .
```

### 3.4 Zainstaluj zależności PHP
```bash
composer install --no-dev --optimize-autoloader
```

### 3.5 Skopiuj i skonfiguruj .env
```bash
cp .env.dhosting .env
nano .env
```

**Wypełnij w .env:**
```env
APP_KEY=                          # Wygeneruj w następnym kroku
APP_URL=https://twoja-domena.pl   # Twoja domena

DB_DATABASE=nazwa_bazy            # Z panelu dhosting
DB_USERNAME=uzytkownik            # Z panelu dhosting
DB_PASSWORD=haslo                 # Z panelu dhosting
```

### 3.6 Wygeneruj klucz aplikacji
```bash
php artisan key:generate
```

### 3.7 Uruchom migracje
```bash
php artisan migrate --force
```

### 3.8 Utwórz administratora
```bash
php artisan db:seed --force
```

Lub ręcznie:
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name' => 'Administrator',
    'email' => 'admin@medvita.pl',
    'password' => bcrypt('TwojeHaslo123!'),
    'role' => 'admin',
    'is_active' => true,
    'email_verified_at' => now(),
]);
exit
```

### 3.9 Utwórz link do storage
```bash
php artisan storage:link
```

### 3.10 Ustaw uprawnienia
```bash
chmod -R 775 storage bootstrap/cache
```

### 3.11 Zoptymalizuj aplikację
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## KROK 4: Konfiguracja .htaccess (jeśli domena wskazuje na root)

Jeśli domena wskazuje na `public_html` (nie `public_html/public`):

```bash
# Skopiuj .htaccess.root do głównego katalogu
cp .htaccess.root .htaccess
```

Lub skonfiguruj domenę w panelu dhosting aby wskazywała na `public_html/public`.

---

## KROK 5: Weryfikacja

### 5.1 Sprawdź stronę główną
Otwórz w przeglądarce: `https://twoja-domena.pl`

### 5.2 Sprawdź panel admina
Otwórz: `https://twoja-domena.pl/admin`

Zaloguj się:
- Email: `admin@medvita.pl`
- Hasło: `Admin123!` (lub Twoje własne)

---

## Aktualizacja aplikacji

Po zmianach w kodzie:

```bash
cd ~/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Rozwiązywanie problemów

### Błąd 500 - Internal Server Error
```bash
# Sprawdź logi
tail -f storage/logs/laravel.log

# Napraw uprawnienia
chmod -R 775 storage bootstrap/cache
```

### Błąd "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Biała strona
```bash
# Włącz debug tymczasowo
nano .env
# Ustaw: APP_DEBUG=true
# Po naprawieniu ustaw z powrotem: APP_DEBUG=false
```

### Problemy z bazą danych
```bash
# Sprawdź połączenie
php artisan tinker
>>> DB::connection()->getPdo();
```

---

## Struktura katalogów na dhosting

```
~/public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← Domena powinna wskazywać TUTAJ
│   ├── build/       ← Zbudowane assety (JS, CSS)
│   ├── index.php
│   └── .htaccess
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── composer.json
```

---

## Kontakt w razie problemów

Jeśli coś nie działa, sprawdź:
1. Logi: `storage/logs/laravel.log`
2. Konfigurację PHP w panelu dhosting (wymagane PHP 8.2+)
3. Uprawnienia katalogów storage i bootstrap/cache
