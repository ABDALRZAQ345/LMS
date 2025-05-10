#!/bin/bash




composer install --no-dev --optimize-autoloader --no-scripts
php artisan package:discover --ansi
php artisan key:generate


php artisan migrate:fresh --seed
php artisan queue:table


php artisan queue:work --tries=3 --timeout=90 &
exec "$@"
