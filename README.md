# Domino-App API

Laravel REST API (Sanctum token auth) for a Domino's-style pizza ordering mobile app.

## Features done
- Register, login, logout, current user
- Categories and products (search, filters, pagination)
- Cart (add, update quantity, remove, clear)

## Setup
composer install
copy .env.example .env
php artisan key:generate
# set DB_* values in .env, then:
php artisan migrate --seed
php artisan serve