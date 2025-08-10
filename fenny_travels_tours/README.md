# Fenny Travels & Tours

Multi-page travel agency website with a lightweight PHP backend, JSON-based settings (DB optional), and an admin dashboard.

## Quick Start
- Requirements: PHP 8+, Node optional, MySQL optional
- Run locally:
  - `php -S localhost:8000 -t /workspace/fenny_travels_tours`
- Admin:
  - URL: `/admin/login.php`
  - Default user: `admin@local`
  - Default password: `admin123`

## Configure
- General and design settings in the Admin Dashboard
- Optional DB: copy `.env.example` to `.env`, adjust DB creds, import `database/schema.sql`

## Flight Data
- View-only results powered by a mock adapter with pluggable providers (Aviationstack, OpenSky, Amadeus, etc.). Configure provider and API key in Admin.

## Structure
- `partials/` shared layout
- `services/` helpers, flight API adapter, mailer
- `assets/` CSS/JS/images
- `admin/` dashboard and settings
- `handlers/` form submissions
