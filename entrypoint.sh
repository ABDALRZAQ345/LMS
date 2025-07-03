#!/bin/bash




composer install  --optimize-autoloader --no-scripts
#--no-dev
php artisan package:discover --ansi
php artisan key:generate

#php artisan queue:table
php artisan migrate:fresh --seed


mkdir -p storage/app/public

php artisan storage:link || true
php artisan schedule:work &

php artisan queue:work --tries=3 --timeout=90 &
exec "$@"
