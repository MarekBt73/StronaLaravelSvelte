#!/bin/bash
# ===========================================
# MedVita - Skrypt deploymentu dla dhosting
# ===========================================

set -e

echo "=========================================="
echo "   MedVita - Deployment Script"
echo "=========================================="

# Kolory
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Sprawdź czy jesteśmy w dobrym katalogu
if [ ! -f "artisan" ]; then
    echo "BŁĄD: Uruchom skrypt z katalogu głównego Laravel!"
    exit 1
fi

echo -e "${YELLOW}[1/7] Pobieranie zmian z Git...${NC}"
git pull origin main

echo -e "${YELLOW}[2/7] Instalacja zależności Composer...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${YELLOW}[3/7] Migracja bazy danych...${NC}"
php artisan migrate --force

echo -e "${YELLOW}[4/7] Czyszczenie cache...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo -e "${YELLOW}[5/7] Budowanie cache...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "${YELLOW}[6/7] Link do storage...${NC}"
php artisan storage:link 2>/dev/null || true

echo -e "${YELLOW}[7/7] Ustawianie uprawnień...${NC}"
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo -e "${GREEN}=========================================="
echo "   Deployment zakończony pomyślnie!"
echo "==========================================${NC}"
echo ""
echo "Sprawdź stronę: $(grep APP_URL .env | cut -d '=' -f2)"
