# pbs-telkom-app

## PBS / PPL readiness work

This repository is a simple Telkom customer manager. To make it meet requirements for:

- **Pemrograman Berorientasi Servis (PBS)** — I added a minimal JSON API at `api.php` and improved controller methods to accept JSON and return structured responses. This demonstrates a service-oriented interface and enables AJAX usage from the UI.
- **Pengujian Perangkat Lunak (PPL)** — I added a validation helper (`lib/Validation.php`) and PHPUnit configuration with unit tests (`tests/ValidationTest.php`) so you have test artifacts and automated checks.

Files added/modified for PBS/PPL readiness:

- `lib/Validation.php` — input validation logic (unit-testable).
- `controllers/PelangganController.php` — improved validation and JSON responses for `create` and `update`.
- `api.php` — JSON endpoints for listing and creating/updating/deleting pelanggan.
- `phpunit.xml` and `composer.json` — PHPUnit config and dev dependency hint.
- `tests/ValidationTest.php` — unit tests for validation rules (run with PHPUnit).

Quick start (PHP built-in server):

```bash
php -S localhost:8000
```

API examples:

```bash
# list pelanggan
curl http://localhost:8000/api.php?action=listPelanggan

# create pelanggan (JSON)
curl -X POST -H "Content-Type: application/json" \
	-d '{"no_internet":"001","nama":"A","no_tlp":"0812","layanan":"HSI50","harga":100000,"tagihan":"lunas","status":"aktif"}' \
	http://localhost:8000/api.php?action=createPelanggan
```

Run unit tests (after installing dependencies):

```bash
composer install --dev
vendor/bin/phpunit --configuration phpunit.xml
```

If you cannot install Composer, run the lightweight test runner instead (no Composer needed):

```bash
php tests/run_tests_no_composer.php
```

Or download the PHPUnit PHAR and run it without Composer:

```bash
curl -L -o phpunit.phar https://phar.phpunit.de/phpunit-9.5.phar
php phpunit.phar --configuration phpunit.xml
```

If you want, I can continue with:

- adding AJAX calls in `views/dashboard.php` to consume `api.php` (PBS evidence),
- writing integration tests that exercise DB-backed endpoints (PPL evidence), or
- adding a short architecture doc describing the service boundaries.
