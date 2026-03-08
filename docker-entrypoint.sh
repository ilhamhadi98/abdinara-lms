#!/bin/sh
set -e

# Salin .env jika belum ada
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Sesuaikan konfigurasi .env
sed -i 's/^DB_HOST=.*/DB_HOST=mysql/' .env
sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=abdinara_lms_2/' .env
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=root/' .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=root/' .env

if grep -q '^OCTANE_SERVER=' .env; then
  sed -i 's/^OCTANE_SERVER=.*/OCTANE_SERVER=roadrunner/' .env
else
  echo 'OCTANE_SERVER=roadrunner' >> .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

# Tunggu database siap dan jalankan migrasi
until php artisan migrate --force; do
  echo "Waiting for MySQL..."
  sleep 3
done

# Buat database testing
mysql -h mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS abdinara_lms_testing;" 2>/dev/null || true

# Seeding & Setup role jika diperlukan
php artisan db:seed --class=RolePermissionSeeder --force || true
php artisan make:super-admin --name="Admin" --email="admin@abdinara.id" --password="@ANiam1998" || true
php artisan storage:link --force || true

# Optimasi / Cache
php artisan config:cache
php artisan view:cache

# RoadRunner
if [ ! -f ./rr ]; then
  ./vendor/bin/rr get-binary
fi

exec php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=8000
