# Cannypedia

Cannypedia is a Laravel sandbox for browsing sample game, mobile-credit, and e-wallet top-ups. Guest checkout creates a local pending transaction, then the payment page lets a tester mark it successful or failed.

No real payment is collected, and no payment gateway or product-provider API is connected.

## Local setup

```sh
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Open the local address printed by Artisan. Sample products and prices are for testing only.

## Test

```sh
php artisan test
```

Tailwind CSS and Alpine.js are loaded from the main Blade layout CDN. The application keeps Laravel's default users table available for future administration, but the storefront, checkout, payment simulation, and invoice lookup do not require login.
