#!/usr/bin/env python3
import os
import json
import base64
from pathlib import Path

PROJECT_ROOT = Path(os.environ.get("FENNY_SITE_DIR", "/workspace/fenny_travels_tours")).resolve()

BUSINESS = {
    "name": "Fenny Travels & Tours",
    "address": "19, Durban Street, Off Ademolade Tokunbo, Wuse 2, Abuja, Nigeria",
    "phone": "0706 054 6145",
    "email": "admin@example.com",
}

COLORS = {
    "primary": "#004AAD",
    "secondary": "#FFD700",
    "accent": "#87CEFA",
    "bg_light": "#F5F9FF",
}

FONTS = {
    "heading": "Poppins",
    "body": "Open Sans",
}

# --------------------------------------------------------------------------------------
# Helpers
# --------------------------------------------------------------------------------------

def ensure_dir(path: Path):
    path.mkdir(parents=True, exist_ok=True)


def write_file(path: Path, content: str, binary: bool = False):
    ensure_dir(path.parent)
    mode = "wb" if binary else "w"
    with open(path, mode, encoding=None if binary else "utf-8") as f:
        if binary:
            f.write(content)
        else:
            f.write(content.strip() + "\n")
    print(f"Wrote: {path}")


def svg_placeholder(width: int, height: int, title: str, gradient_start: str, gradient_end: str) -> str:
    return f"""
<svg width="{width}" height="{height}" viewBox="0 0 {width} {height}" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="{title}">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{gradient_start}"/>
      <stop offset="100%" stop-color="{gradient_end}"/>
    </linearGradient>
    <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
      <feGaussianBlur stdDeviation="12" result="coloredBlur"/>
      <feMerge>
        <feMergeNode in="coloredBlur"/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
  </defs>
  <rect width="100%" height="100%" fill="url(#grad)"/>
  <g filter="url(#glow)">
    <text x="50%" y="45%" text-anchor="middle" font-family="{FONTS['heading']}" font-size="{int(min(width, height) * 0.12)}" fill="#ffffff" opacity="0.95">{title}</text>
    <text x="50%" y="65%" text-anchor="middle" font-family="{FONTS['body']}" font-size="{int(min(width, height) * 0.06)}" fill="#ffffff" opacity="0.85">Fenny Travels &amp; Tours</text>
  </g>
</svg>
"""


# --------------------------------------------------------------------------------------
# Content strings (PHP, CSS, JS)
# --------------------------------------------------------------------------------------

README_MD = f"""
# {BUSINESS['name']}

Multi-page travel agency website with a lightweight PHP backend, JSON-based settings (DB optional), and an admin dashboard.

## Quick Start
- Requirements: PHP 8+, Node optional, MySQL optional
- Run locally:
  - `php -S localhost:8000 -t {PROJECT_ROOT}`
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
"""

ENV_EXAMPLE = """
# Copy to .env and adjust
APP_ENV=local
APP_DEBUG=true

# Optional MySQL (JSON storage is default if not set)
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fenny_travels
DB_USERNAME=fenny
DB_PASSWORD=secret

# Mail (mailer falls back to logs if not configured)
MAIL_FROM=bookings@fennytravels.example
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
"""

CONFIG_DB_PHP = """
<?php
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $env = parse_ini_file($dotenvPath, false, INI_SCANNER_RAW);
} else {
    $env = [];
}

$DB_HOST = $env['DB_HOST'] ?? null;
$DB_PORT = $env['DB_PORT'] ?? '3306';
$DB_DATABASE = $env['DB_DATABASE'] ?? null;
$DB_USERNAME = $env['DB_USERNAME'] ?? null;
$DB_PASSWORD = $env['DB_PASSWORD'] ?? null;

$conn = null;
if ($DB_HOST && $DB_DATABASE && $DB_USERNAME !== null) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE, (int)$DB_PORT);
    if ($conn && $conn->connect_errno) {
        $conn = null;
    }
}
"""

CONFIG_CONFIG_PHP = f"""
<?php
require_once __DIR__ . '/db.php';

function storage_dir(): string {{
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) {{ mkdir($dir, 0775, true); }}
    return $dir;
}}

function options_path(): string {{
    $dir = storage_dir();
    return $dir . '/settings.json';
}}

function read_options(): array {{
    $path = options_path();
    if (!file_exists($path)) {{
        $defaults = [
            'business' => [
                'name' => '{BUSINESS['name']}',
                'address' => '{BUSINESS['address']}',
                'phone' => '{BUSINESS['phone']}',
                'email' => '{BUSINESS['email']}'
            ],
            'theme' => [
                'primary' => '{COLORS['primary']}',
                'secondary' => '{COLORS['secondary']}',
                'accent' => '{COLORS['accent']}',
                'bg_light' => '{COLORS['bg_light']}',
                'heading_font' => '{FONTS['heading']}',
                'body_font' => '{FONTS['body']}'
            ],
            'flight' => [
                'provider' => 'mock',
                'api_key' => '',
                'enabled_trip_types' => ['oneway', 'round', 'multicity'],
                'enabled_classes' => ['economy', 'business', 'first']
            ],
            'notifications' => [
                'email' => '{BUSINESS['email']}',
                'sms' => ''
            ],
            'admin' => [
                'email' => 'admin@local',
                // password: admin123
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT)
            ],
            'content' => [
                'about' => 'We provide domestic and international flight booking, consultancy, hotels, visas, and insurance. Enjoy seamless, affordable travel experiences with us.',
                'services' => [
                    ['title' => 'Air Ticket Booking', 'desc' => 'Domestic and international tickets with flexible options and competitive fares.'],
                    ['title' => 'Travel Consultancy', 'desc' => 'Personalized itineraries, best times to travel, and hidden-gem recommendations.'],
                    ['title' => 'Hotel Reservations', 'desc' => 'Curated stays from budget to luxury with exclusive partner rates.'],
                    ['title' => 'Visa Assistance', 'desc' => 'End-to-end document support and interview preparation.'],
                    ['title' => 'Travel Insurance', 'desc' => 'Comprehensive coverage for peace of mind wherever you go.']
                ],
                'faq' => [
                    ['q' => 'Can I book multi-city trips?', 'a' => 'Absolutely. Use the multi-city option in our search widget to plan complex itineraries.'],
                    ['q' => 'Do you handle visa services?', 'a' => 'Yes, we support documentation, application, and interview preparation.'],
                    ['q' => 'How do I get travel insurance?', 'a' => 'Select insurance during booking or ask our consultants for tailored coverage.']
                ],
                'testimonials' => [
                    ['name' => 'Amaka I.', 'text' => 'Smooth booking and excellent support. Highly recommended!', 'rating' => 5],
                    ['name' => 'David O.', 'text' => 'Great deals and fast response time. Loved it!', 'rating' => 4],
                    ['name' => 'Fatima N.', 'text' => 'Visa guidance was spot on. Thank you!', 'rating' => 5]
                ],
                'destinations' => [
                    ['city' => 'Lagos', 'country' => 'Nigeria', 'price' => 120, 'image' => 'assets/images/destination_lagos.svg'],
                    ['city' => 'London', 'country' => 'United Kingdom', 'price' => 620, 'image' => 'assets/images/destination_london.svg'],
                    ['city' => 'Dubai', 'country' => 'UAE', 'price' => 540, 'image' => 'assets/images/destination_dubai.svg'],
                    ['city' => 'Nairobi', 'country' => 'Kenya', 'price' => 280, 'image' => 'assets/images/destination_nairobi.svg']
                ]
            ]
        ];
        @file_put_contents($path, json_encode($defaults, JSON_PRETTY_PRINT));
        return $defaults;
    }}
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}}

function save_options(array $data): bool {{
    $path = options_path();
    return (bool) @file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}}

function get_option(string $path, $default = null) {{
    $data = read_options();
    $parts = explode('.', $path);
    foreach ($parts as $p) {{
        if (!is_array($data) || !array_key_exists($p, $data)) {{ return $default; }}
        $data = $data[$p];
    }}
    return $data;
}}

function set_option(string $path, $value): bool {{
    $data = read_options();
    $ref =& $data;
    $parts = explode('.', $path);
    foreach ($parts as $p) {{
        if (!isset($ref[$p]) || !is_array($ref[$p])) {{ $ref[$p] = []; }}
        $ref =& $ref[$p];
    }}
    $ref = $value;
    return save_options($data);
}}
"""

SERVICES_HELPERS_PHP = """
<?php
require_once __DIR__ . '/../config/config.php';

function is_post(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function is_get(): bool { return $_SERVER['REQUEST_METHOD'] === 'GET'; }

function redirect(string $to): void { header('Location: ' . $to); exit; }

function e(string $val): string { return htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); }

function json_response($data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function storage_path(string $relative): string {
    $base = storage_dir();
    $path = rtrim($base, '/\\') . '/' . ltrim($relative, '/\\');
    $dir = dirname($path);
    if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
    return $path;
}

function write_log(string $file, string $message): void {
    $path = storage_path('logs/' . $file);
    @file_put_contents($path, '[' . date('c') . "] " . $message . "\n", FILE_APPEND);
}
"""

SERVICES_MAILER_PHP = """
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/helpers.php';

function send_mail(string $to, string $subject, string $html, string $from = null): bool {
    $from = $from ?: (get_option('notifications.email') ?: 'no-reply@localhost');

    // Very basic mail() usage; on many dev setups this may not send real mail.
    // We always log emails to storage/logs/mailer.log
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: $from\r\n";

    $ok = @mail($to, $subject, $html, $headers);
    write_log('mailer.log', "To=$to | Subject=$subject | Sent=" . ($ok ? 'yes' : 'no') . "\n---\n$html\n---\n");
    return $ok;
}
"""

SERVICES_FLIGHT_API_PHP = """
<?php
require_once __DIR__ . '/../config/config.php';

// View-only flight data adapter. Real API calls can be added per provider selection.
function fetch_view_only_flights(array $criteria): array {
    $provider = get_option('flight.provider', 'mock');
    $apiKey = get_option('flight.api_key', '');

    // Example: switch over providers (mock only for now)
    switch ($provider) {
        default:
            return mock_flight_results($criteria);
    }
}

function mock_flight_results(array $criteria): array {
    $from = strtoupper($criteria['from'] ?? 'ABV');
    $to = strtoupper($criteria['to'] ?? 'LOS');
    $date = $criteria['depart_date'] ?? date('Y-m-d');
    $class = ucfirst($criteria['cabin'] ?? 'Economy');

    $carriers = ['Air Peace', 'Arik Air', 'Ibom Air', 'British Airways', 'Qatar Airways', 'Emirates'];
    $durations = ['1h 10m', '1h 25m', '2h 05m', '6h 30m', '8h 45m'];
    $prices = [120, 150, 180, 450, 620, 820];

    $results = [];
    for ($i = 0; $i < 8; $i++) {
        $carrier = $carriers[array_rand($carriers)];
        $duration = $durations[array_rand($durations)];
        $price = $prices[array_rand($prices)];
        $dep = date('H:i', strtotime("+" . rand(6, 36) . " minutes"));
        $arr = date('H:i', strtotime("+" . rand(80, 480) . " minutes"));
        $results[] = [
            'id' => uniqid('flt_'),
            'carrier' => $carrier,
            'from' => $from,
            'to' => $to,
            'depart_time' => $dep,
            'arrive_time' => $arr,
            'duration' => $duration,
            'stops' => rand(0, 1),
            'cabin' => $class,
            'price' => $price,
            'currency' => 'USD',
            'date' => $date,
        ];
    }
    return $results;
}
"""

PARTIALS_HEAD_PHP = f"""
<?php
$opts = read_options();
$biz = $opts['business'];
$theme = $opts['theme'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo e($biz['name']); ?></title>
  <meta name="description" content="Seamless and affordable travel experiences: flights, hotels, visas, insurance." />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family={FONTS['heading'].replace(' ', '+')}:wght@400;600;700;800&family={FONTS['body'].replace(' ', '+')}:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>" />
  <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml" />
  <style>
    :root {{
      --primary: <?php echo $theme['primary']; ?>;
      --secondary: <?php echo $theme['secondary']; ?>;
      --accent: <?php echo $theme['accent']; ?>;
      --bg-light: <?php echo $theme['bg_light']; ?>;
      --heading-font: '{FONTS['heading']}';
      --body-font: '{FONTS['body']}';
    }}
  </style>
</head>
<body class="bg-gradient">
"""

PARTIALS_NAVBAR_PHP = """
<?php $biz = get_option('business'); ?>
<nav id="mainNav" class="navbar navbar-expand-lg navbar-dark fixed-top nav-glass">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/index.php">
      <img src="/assets/images/logo.svg" alt="Logo" height="36" class="me-2" />
      <?php echo e($biz['name']); ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="/faq.php">FAQ</a></li>
        <li class="nav-item"><a class="nav-link" href="/testimonials.php">Testimonials</a></li>
        <li class="nav-item"><a class="nav-link" href="/contact.php">Contact</a></li>
        <li class="nav-item ms-lg-2"><a class="btn btn-gold btn-sm px-3" href="/index.php#search">Book Now</a></li>
      </ul>
    </div>
  </div>
</nav>
"""

PARTIALS_FOOTER_PHP = f"""
<?php $biz = get_option('business'); ?>
<footer class="pt-5 pb-4 mt-5 footer-glass">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6">
        <h5 class="fw-bold">{BUSINESS['name']}</h5>
        <p class="mb-2"><span class="text-muted">Address:</span> <?php echo e($biz['address']); ?></p>
        <p class="mb-2"><span class="text-muted">Phone/WhatsApp:</span> <?php echo e($biz['phone']); ?></p>
        <p class="mb-0"><span class="text-muted">Email:</span> <?php echo e($biz['email']); ?></p>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold">Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="/about.php" class="link-light">About</a></li>
          <li><a href="/services.php" class="link-light">Services</a></li>
          <li><a href="/faq.php" class="link-light">FAQ</a></li>
          <li><a href="/testimonials.php" class="link-light">Testimonials</a></li>
          <li><a href="/contact.php" class="link-light">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold">Follow Us</h6>
        <div class="d-flex gap-3">
          <a class="social glow" href="#" aria-label="Facebook"><span class="bi bi-facebook"></span></a>
          <a class="social glow" href="#" aria-label="Instagram"><span class="bi bi-instagram"></span></a>
          <a class="social glow" href="#" aria-label="Twitter"><span class="bi bi-twitter"></span></a>
          <a class="social glow" href="#" aria-label="LinkedIn"><span class="bi bi-linkedin"></span></a>
        </div>
      </div>
    </div>
    <div class="text-center mt-4 small opacity-75">&copy; <?php echo date('Y'); ?> {BUSINESS['name']} · All rights reserved.</div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
<script src="/assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
"""

ASSETS_CSS = f"""
:root {{
  --shadow-lg: 0 30px 60px -15px rgba(0,0,0,0.35);
  --glass-bg: rgba(255, 255, 255, 0.12);
  --glass-brd: rgba(255, 255, 255, 0.25);
}}

* {{ box-sizing: border-box; }}

html, body {{
  font-family: var(--body-font), system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji";
  color: #0b1736;
  background: linear-gradient(135deg, #eff5ff 0%, #ffffff 40%, #f7fbff 100%);
  scroll-behavior: smooth;
}}

h1, h2, h3, h4, h5, h6 {{
  font-family: var(--heading-font), sans-serif;
  letter-spacing: 0.3px;
}}

.bg-gradient {{
  background: radial-gradient(1200px 600px at 10% -10%, rgba(0,74,173,0.10) 0%, rgba(0,74,173,0) 60%),
              radial-gradient(1000px 800px at 110% 10%, rgba(255,215,0,0.13) 0%, rgba(255,215,0,0) 60%),
              linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
}}

.nav-glass {{
  background: linear-gradient(180deg, rgba(10, 22, 56, 0.8), rgba(10, 22, 56, 0.55));
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255,255,255,0.12);
  transition: background-color 0.25s ease, box-shadow 0.25s ease;
}}

.nav-glass.scrolled {{
  background: rgba(10, 22, 56, 0.95);
  box-shadow: var(--shadow-lg);
}}

.btn-gold {{
  --bs-btn-bg: {COLORS['secondary']};
  --bs-btn-border-color: {COLORS['secondary']};
  --bs-btn-hover-bg: #f0c200;
  --bs-btn-hover-border-color: #f0c200;
  --bs-btn-color: #0b1736;
  --bs-btn-hover-color: #0b1736;
  font-weight: 700;
  box-shadow: 0 10px 25px -8px rgba(255,215,0,0.65);
}}

.card-glass {{
  background: var(--glass-bg);
  border: 1px solid var(--glass-brd);
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  backdrop-filter: blur(10px);
}}

.footer-glass {{
  background: linear-gradient(180deg, rgba(0, 74, 173, 0.06), rgba(0,74,173,0.02));
  border-top: 1px solid rgba(0,0,0,0.06);
}}

.hero {{
  position: relative;
  min-height: 88vh;
  display: grid;
  place-items: center;
  overflow: hidden;
}}
.hero .hero-bg {{
  position: absolute;
  inset: 0;
  background-image: url('/assets/images/hero.svg');
  background-size: cover;
  background-position: center;
  filter: saturate(1.1) contrast(1.05);
}}
.hero .overlay {{
  position: absolute;
  inset: 0;
  background: radial-gradient(600px 300px at 20% 10%, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0) 60%),
              linear-gradient(180deg, rgba(0,0,0,0.0), rgba(0,0,0,0.45));
}}
.hero .content {{
  position: relative;
  z-index: 2;
  text-align: center;
  color: #fff;
  padding: 2rem;
}}
.hero h1 {{
  font-size: clamp(2rem, 4vw + 1rem, 4rem);
  font-weight: 800;
  text-shadow: 0 10px 30px rgba(0,0,0,0.35);
}}
.hero p.lead {{
  opacity: 0.95;
  font-weight: 500;
}}

.search-widget {{
  position: relative;
  z-index: 2;
  margin-top: -4rem;
  border-radius: 20px;
  box-shadow: 0 30px 80px -20px rgba(0,0,0,0.35);
}}

.neu {{
  background: linear-gradient(145deg, #ffffff, #eef3ff);
  border-radius: 18px;
  box-shadow: 20px 20px 60px #c8d2e0, -20px -20px 60px #ffffff;
}}

.glow-hover {{
  transition: transform .25s ease, box-shadow .25s ease;
}}
.glow-hover:hover {{
  transform: translateY(-6px);
  box-shadow: 0 20px 60px -20px rgba(0, 74, 173, 0.55), 0 0 0 2px rgba(255,215,0,0.6);
}}

.section-title {{
  font-weight: 800;
  letter-spacing: 0.3px;
}}

.counter {{
  font-weight: 800;
  color: var(--primary);
}}

.dest-card img {{
  border-radius: 14px;
  transition: transform .35s ease;
}}
.dest-card:hover img {{ transform: scale(1.06); }}

.badge-glass {{
  background: rgba(255, 255, 255, 0.6);
  border: 1px solid rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(6px);
  color: #0b1736;
}}

.social.glow {{
  display: inline-flex; align-items: center; justify-content: center;
  width: 40px; height: 40px; border-radius: 50%;
  background: rgba(255,255,255,0.15); color: #102050;
  box-shadow: inset 0 0 0 1px rgba(0,0,0,0.06);
  transition: transform .2s ease, box-shadow .2s ease;
}}
.social.glow:hover {{ transform: translateY(-4px); box-shadow: 0 12px 30px -10px rgba(0,0,0,0.35); }}

.accordion-button:not(.collapsed) {{
  background-color: rgba(0,74,173,0.06);
  color: #0b1736;
}}

.testimonial {{
  background: linear-gradient(145deg, #ffffff, #eef3ff);
  border-radius: 16px;
  padding: 1.25rem;
  border: 1px solid rgba(0,0,0,0.06);
}}

.rating .star {{ color: #f3b600; }}
"""

ASSETS_JS = """
(function(){
  const nav = document.getElementById('mainNav');
  const onScroll = () => {
    if (!nav) return;
    if (window.scrollY > 24) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
  };
  document.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (window.AOS) { AOS.init({ once: true, duration: 700, easing: 'ease-out-cubic' }); }

  if (window.gsap && window.ScrollTrigger) {
    const bg = document.querySelector('.hero .hero-bg');
    if (bg) {
      gsap.to(bg, { yPercent: 12, ease: 'none', scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true } });
    }
  }

  const counters = document.querySelectorAll('[data-counter]');
  counters.forEach(el => {
    const target = parseInt(el.getAttribute('data-counter') || '0', 10);
    let current = 0; const step = Math.max(1, Math.round(target / 120));
    const tick = () => {
      current += step; if (current >= target) { current = target; }
      el.textContent = current.toString();
      if (current < target) requestAnimationFrame(tick);
    };
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { tick(); io.disconnect(); } });
    }, { threshold: 0.4 });
    io.observe(el);
  });

  const stars = document.querySelectorAll('[data-stars]');
  stars.forEach(el => {
    const n = parseInt(el.getAttribute('data-stars') || '0', 10);
    el.innerHTML = '★★★★★'.slice(0, n).split('').map(s => `<span class="star">${s}</span>`).join('');
  });
})();
"""

INDEX_PHP = """
<?php
require_once __DIR__ . '/services/helpers.php';
require_once __DIR__ . '/services/flight_api.php';
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<header class="hero">
  <div class="hero-bg" aria-hidden="true"></div>
  <div class="overlay" aria-hidden="true"></div>
  <div class="content container" data-aos="fade-up">
    <span class="badge badge-glass mb-3 px-3 py-2 fw-semibold">Seamless · Enjoyable · Affordable</span>
    <h1>Fly Smarter with <span style="color: var(--secondary);">Fenny</span> — Your Journey Starts Here</h1>
    <p class="lead mt-2">Domestic & international flights, hotels, visas and travel insurance backed by real experts.</p>
    <a href="#search" class="btn btn-gold btn-lg mt-3 glow-hover">Search Flights</a>
  </div>
</header>

<section id="search" class="container position-relative">
  <div class="card card-glass p-4 p-md-5 search-widget" data-aos="fade-up">
    <form class="row g-3" action="/search.php" method="get">
      <div class="col-12 col-md-3">
        <label class="form-label fw-semibold">Trip Type</label>
        <select name="trip" class="form-select">
          <option value="round">Round Trip</option>
          <option value="oneway">One-way</option>
          <option value="multicity">Multi-city</option>
        </select>
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">From</label>
        <input type="text" class="form-control" name="from" placeholder="ABV" required />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">To</label>
        <input type="text" class="form-control" name="to" placeholder="LOS" required />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Depart</label>
        <input type="date" class="form-control" name="depart_date" required />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Return</label>
        <input type="date" class="form-control" name="return_date" />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Cabin</label>
        <select class="form-select" name="cabin">
          <option value="economy">Economy</option>
          <option value="business">Business</option>
          <option value="first">First Class</option>
        </select>
      </div>
      <div class="col-4 col-md-2">
        <label class="form-label fw-semibold">Adults</label>
        <input type="number" min="1" value="1" class="form-control" name="adults" />
      </div>
      <div class="col-4 col-md-2">
        <label class="form-label fw-semibold">Children</label>
        <input type="number" min="0" value="0" class="form-control" name="children" />
      </div>
      <div class="col-4 col-md-2">
        <label class="form-label fw-semibold">Infants</label>
        <input type="number" min="0" value="0" class="form-control" name="infants" />
      </div>
      <div class="col-12 col-md-2 d-grid">
        <label class="form-label opacity-0">&nbsp;</label>
        <button class="btn btn-gold btn-lg">Search</button>
      </div>
    </form>
  </div>
</section>

<section class="container py-6">
  <div class="text-center mb-4" data-aos="fade-up">
    <h2 class="section-title">Featured Destinations</h2>
    <p class="text-muted">Curated deals handpicked by our travel consultants</p>
  </div>
  <div class="row g-4">
    <?php foreach (get_option('content.destinations', []) as $d): ?>
      <div class="col-12 col-sm-6 col-lg-3" data-aos="zoom-in">
        <div class="dest-card glow-hover neu p-2 h-100">
          <img src="/<?php echo e($d['image']); ?>" class="w-100" alt="<?php echo e($d['city']); ?>" />
          <div class="p-3">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 fw-bold"><?php echo e($d['city'] . ', ' . $d['country']); ?></h5>
              <span class="badge text-bg-warning">$<?php echo e($d['price']); ?></span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-md-6" data-aos="fade-right">
      <h3 class="section-title">Why Travel with Us</h3>
      <p>With years of experience across routes worldwide, we craft journeys that are reliable, flexible, and affordable. From tickets to visas and insurance, we simplify everything so you can focus on the memories.</p>
      <ul class="list-unstyled">
        <li class="mb-2">✔ Expert travel consultants</li>
        <li class="mb-2">✔ Exclusive partner deals</li>
        <li class="mb-2">✔ End-to-end itinerary support</li>
      </ul>
    </div>
    <div class="col-md-6" data-aos="fade-left">
      <div class="row g-3 text-center">
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="8">0</div><div>Years of Service</div></div></div>
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="120">0</div><div>Destinations</div></div></div>
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="4500">0</div><div>Happy Customers</div></div></div>
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="30">0</div><div>Partner Airlines</div></div></div>
      </div>
    </div>
  </div>
</section>

<section class="container py-6">
  <div class="text-center mb-4" data-aos="fade-up">
    <h2 class="section-title">Latest Travel Deals</h2>
    <p class="text-muted">Limited-time offers — book a consultation</p>
  </div>
  <div class="row g-4">
    <?php for ($i=0;$i<6;$i++): ?>
    <div class="col-12 col-md-6 col-lg-4" data-aos="zoom-in">
      <div class="card h-100 card-glass glow-hover">
        <div class="card-body p-4">
          <span class="badge text-bg-primary mb-2">Special</span>
          <h5 class="card-title fw-bold">Flash Deal #<?php echo $i+1; ?></h5>
          <p class="card-text">Save up to 20% on select routes. Talk to our consultants for tailored, cost-effective options.</p>
          <a href="#search" class="stretched-link"></a>
        </div>
      </div>
    </div>
    <?php endfor; ?>
  </div>
</section>

<section class="container py-6">
  <div class="text-center mb-4" data-aos="fade-up">
    <h2 class="section-title">What Clients Say</h2>
    <p class="text-muted">Real feedback from happy travelers</p>
  </div>
  <div class="row g-4">
    <?php foreach (get_option('content.testimonials', []) as $t): ?>
      <div class="col-12 col-md-4" data-aos="fade-up">
        <div class="testimonial glow-hover h-100">
          <div class="rating mb-2" data-stars="<?php echo (int)$t['rating']; ?>"></div>
          <p class="mb-2">"<?php echo e($t['text']); ?>"</p>
          <div class="fw-bold">— <?php echo e($t['name']); ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

SEARCH_PHP = """
<?php
require_once __DIR__ . '/services/helpers.php';
require_once __DIR__ . '/services/flight_api.php';

$criteria = [
  'trip' => $_GET['trip'] ?? 'round',
  'from' => $_GET['from'] ?? 'ABV',
  'to' => $_GET['to'] ?? 'LOS',
  'depart_date' => $_GET['depart_date'] ?? date('Y-m-d'),
  'return_date' => $_GET['return_date'] ?? null,
  'cabin' => $_GET['cabin'] ?? 'economy',
  'adults' => (int) ($_GET['adults'] ?? 1),
  'children' => (int) ($_GET['children'] ?? 0),
  'infants' => (int) ($_GET['infants'] ?? 0),
];
$results = fetch_view_only_flights($criteria);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="d-flex justify-content-between align-items-end mb-3">
    <div>
      <h3 class="section-title mb-0">Flight Results</h3>
      <div class="text-muted small">From <?php echo e(strtoupper($criteria['from'])); ?> to <?php echo e(strtoupper($criteria['to'])); ?> · <?php echo e(ucfirst($criteria['cabin'])); ?> · <?php echo e($criteria['depart_date']); ?></div>
    </div>
    <a href="/index.php#search" class="btn btn-outline-primary">Modify Search</a>
  </div>
  <div class="row g-3">
    <?php foreach ($results as $r): ?>
      <div class="col-12">
        <div class="card card-glass p-3 align-items-center d-flex flex-wrap flex-md-nowrap gap-3">
          <div class="flex-grow-1">
            <div class="fw-bold h5 mb-1"><?php echo e($r['carrier']); ?></div>
            <div class="text-muted small"><?php echo e($r['from']); ?> → <?php echo e($r['to']); ?> · <?php echo e($r['duration']); ?> · <?php echo e($r['stops']); ?> stop(s)
              · <?php echo e($r['depart_time']); ?> - <?php echo e($r['arrive_time']); ?></div>
          </div>
          <div class="text-center px-3">
            <div class="h4 mb-0">$<?php echo e($r['price']); ?></div>
            <div class="small text-muted"><?php echo e($r['currency']); ?></div>
          </div>
          <div class="ms-md-auto">
            <a href="/book.php?id=<?php echo e($r['id']); ?>&from=<?php echo e($r['from']); ?>&to=<?php echo e($r['to']); ?>&carrier=<?php echo urlencode($r['carrier']); ?>&date=<?php echo e($r['date']); ?>&cabin=<?php echo e($r['cabin']); ?>&price=<?php echo e($r['price']); ?>" class="btn btn-gold">Book</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

BOOK_PHP = """
<?php
require_once __DIR__ . '/services/helpers.php';
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <h3 class="section-title">Booking Request</h3>
  <p class="text-muted">Provide your details and preferences. We will contact you to finalize your booking.</p>
  <form action="/handlers/submit_booking.php" method="post" class="row g-3">
    <input type="hidden" name="flight_id" value="<?php echo e($_GET['id'] ?? ''); ?>" />
    <input type="hidden" name="summary" value="<?php echo e(($_GET['carrier'] ?? '') . ' ' . ($_GET['from'] ?? '') . '→' . ($_GET['to'] ?? '') . ' ' . ($_GET['date'] ?? '')); ?>" />
    <div class="col-md-6">
      <label class="form-label">Full Name</label>
      <input class="form-control" name="name" required />
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required />
    </div>
    <div class="col-md-6">
      <label class="form-label">Phone</label>
      <input class="form-control" name="phone" required />
    </div>
    <div class="col-md-6">
      <label class="form-label">Preferred Cabin</label>
      <select class="form-select" name="cabin">
        <option>Economy</option>
        <option>Business</option>
        <option>First</option>
      </select>
    </div>
    <div class="col-12">
      <label class="form-label">Preferences / Notes</label>
      <textarea class="form-control" name="notes" rows="4" placeholder="Seat preference, meal, assistance..."></textarea>
    </div>
    <div class="col-12">
      <button class="btn btn-gold">Submit Request</button>
    </div>
  </form>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

ABOUT_PHP = f"""
<?php
require_once __DIR__ . '/services/helpers.php';
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-lg-6" data-aos="fade-right">
      <h2 class="section-title">About {BUSINESS['name']}</h2>
      <p><?php echo e(get_option('content.about')); ?></p>
      <div class="mt-3">
        <div class="neu p-3 d-inline-block me-3">Trusted Advisors</div>
        <div class="neu p-3 d-inline-block">Global Network</div>
      </div>
    </div>
    <div class="col-lg-6" data-aos="fade-left">
      <img src="/assets/images/team.svg" alt="Team" class="w-100 rounded-4 shadow" />
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

SERVICES_PHP = """
<?php
require_once __DIR__ . '/services/helpers.php';
$services = get_option('content.services', []);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="text-center mb-4">
    <h2 class="section-title">Our Services</h2>
  </div>
  <div class="row g-4">
    <?php foreach ($services as $s): ?>
      <div class="col-12 col-md-6" data-aos="fade-up">
        <div class="card card-glass p-4 h-100">
          <h5 class="fw-bold mb-2"><?php echo e($s['title']); ?></h5>
          <p class="mb-0"><?php echo e($s['desc']); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-md-6">
      <img class="w-100 rounded-4 shadow" src="/assets/images/hotel.svg" alt="Hotel gallery" />
    </div>
    <div class="col-md-6">
      <h3 class="section-title">Hotel Reservations</h3>
      <p>Browse a curated selection across budgets — handpicked for comfort, location, and value.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

FAQ_PHP = """
<?php
require_once __DIR__ . '/services/helpers.php';
$faqs = get_option('content.faq', []);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <h2 class="section-title mb-4">Frequently Asked Questions</h2>
  <div class="accordion" id="faqAccordion">
    <?php foreach ($faqs as $i => $f): $id = 'fq' . $i; ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="h<?php echo $id; ?>">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c<?php echo $id; ?>" aria-expanded="false" aria-controls="c<?php echo $id; ?>">
            <?php echo e($f['q']); ?>
          </button>
        </h2>
        <div id="c<?php echo $id; ?>" class="accordion-collapse collapse" aria-labelledby="h<?php echo $id; ?>" data-bs-parent="#faqAccordion">
          <div class="accordion-body"><?php echo e($f['a']); ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

TESTIMONIALS_PHP = """
<?php
require_once __DIR__ . '/services/helpers.php';
$testimonials = get_option('content.testimonials', []);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="text-center mb-4">
    <h2 class="section-title">Testimonials</h2>
    <p class="text-muted">User-submitted experiences</p>
  </div>
  <div class="row g-4">
    <?php foreach ($testimonials as $t): ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="testimonial glow-hover h-100">
          <div class="rating mb-2" data-stars="<?php echo (int)$t['rating']; ?>"></div>
          <div class="fw-bold mb-1"><?php echo e($t['name']); ?></div>
          <div>"<?php echo e($t['text']); ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

CONTACT_PHP = """
<?php
require_once __DIR__ . '/services/helpers.php';
$biz = get_option('business');
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="row g-4">
    <div class="col-lg-6">
      <h2 class="section-title">Contact Us</h2>
      <p class="text-muted">We usually respond within a few hours.</p>
      <form class="row g-3" method="post" action="/handlers/submit_contact.php">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" required />
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" required />
        </div>
        <div class="col-12">
          <label class="form-label">Message</label>
          <textarea class="form-control" rows="5" name="message" required></textarea>
        </div>
        <div class="col-12">
          <button class="btn btn-gold">Send</button>
        </div>
      </form>
    </div>
    <div class="col-lg-6">
      <div class="ratio ratio-4x3 neu">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.886888894606!2d7.478!3d9.0819999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0b1b5f2d2a39%3A0x0!2sWuse%202%2C%20Abuja!5e0!3m2!1sen!2sNG!4v1700000000000"
          style="border:0; filter: hue-rotate(330deg) saturate(1.2);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="mt-3 small">
        <div><strong>Address:</strong> <?php echo e($biz['address']); ?></div>
        <div><strong>Phone/WhatsApp:</strong> <?php echo e($biz['phone']); ?></div>
        <div><strong>Email:</strong> <?php echo e($biz['email']); ?></div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
"""

HANDLER_BOOKING_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
require_once __DIR__ . '/../services/mailer.php';

if (!is_post()) { redirect('/index.php'); }

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$cabin = trim($_POST['cabin'] ?? '');
$notes = trim($_POST['notes'] ?? '');
$summary = trim($_POST['summary'] ?? '');

$rec = [
  'name' => $name,
  'email' => $email,
  'phone' => $phone,
  'cabin' => $cabin,
  'notes' => $notes,
  'summary' => $summary,
  'ts' => date('c')
];

$destEmail = get_option('notifications.email', 'admin@local');

@file_put_contents(storage_path('requests/booking_' . time() . '.json'), json_encode($rec, JSON_PRETTY_PRINT));

$html = '<h3>New Booking Request</h3>'
      . '<p><strong>Name:</strong> ' . e($name) . '</p>'
      . '<p><strong>Email:</strong> ' . e($email) . '</p>'
      . '<p><strong>Phone:</strong> ' . e($phone) . '</p>'
      . '<p><strong>Cabin:</strong> ' . e($cabin) . '</p>'
      . '<p><strong>Flight:</strong> ' . e($summary) . '</p>'
      . '<p><strong>Notes:</strong> ' . nl2br(e($notes)) . '</p>';

send_mail($destEmail, 'New Booking Request', $html);

redirect('/thankyou.php');
"""

HANDLER_CONTACT_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
require_once __DIR__ . '/../services/mailer.php';

if (!is_post()) { redirect('/index.php'); }

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

$rec = [ 'name' => $name, 'email' => $email, 'message' => $message, 'ts' => date('c') ];
@file_put_contents(storage_path('requests/contact_' . time() . '.json'), json_encode($rec, JSON_PRETTY_PRINT));

$destEmail = get_option('notifications.email', 'admin@local');
$html = '<h3>New Contact Message</h3>'
      . '<p><strong>Name:</strong> ' . e($name) . '</p>'
      . '<p><strong>Email:</strong> ' . e($email) . '</p>'
      . '<p><strong>Message:</strong><br>' . nl2br(e($message)) . '</p>';

send_mail($destEmail, 'New Contact Message', $html);

redirect('/thankyou.php');
"""

THANKYOU_PHP = """
<?php include __DIR__ . '/services/helpers.php'; ?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<section class="container py-5 text-center">
  <div class="neu p-5">
    <h3 class="section-title">Thank You!</h3>
    <p>We have received your request. Our team will reach out shortly.</p>
    <a class="btn btn-gold" href="/index.php">Back to Home</a>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
"""

ADMIN_LOGIN_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
session_start();

if (isset($_SESSION['admin'])) { redirect('/admin/dashboard.php'); }

$error = null;
if (is_post()) {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $admin = get_option('admin');
  if (strtolower($email) === strtolower($admin['email']) && password_verify($password, $admin['password_hash'])) {
    $_SESSION['admin'] = $email;
    redirect('/admin/dashboard.php');
  } else {
    $error = 'Invalid credentials';
  }
}
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<section class="container py-5" style="max-width: 560px;">
  <div class="card card-glass p-4">
    <h3 class="mb-3">Admin Login</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required />
      </div>
      <button class="btn btn-gold w-100">Login</button>
    </form>
  </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
"""

ADMIN_DASHBOARD_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

$requestsDir = storage_path('requests');
$files = glob($requestsDir . '/*.json');
$bookings = array_filter($files, fn($f) => str_contains($f, 'booking_'));
$contacts = array_filter($files, fn($f) => str_contains($f, 'contact_'));
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-4">Admin Dashboard</h3>
  <div class="row g-4">
    <div class="col-md-4"><div class="neu p-4 text-center"><div class="display-5 fw-bold"><?php echo count($bookings); ?></div><div>Booking Requests</div></div></div>
    <div class="col-md-4"><div class="neu p-4 text-center"><div class="display-5 fw-bold"><?php echo count($contacts); ?></div><div>Contact Messages</div></div></div>
    <div class="col-md-4"><div class="neu p-4 text-center"><div class="display-5 fw-bold">1</div><div>Active Admin</div></div></div>
  </div>
  <div class="mt-4">
    <a class="btn btn-primary me-2" href="/admin/settings.php">General Settings</a>
    <a class="btn btn-outline-primary me-2" href="/admin/content.php">Content</a>
    <a class="btn btn-outline-primary" href="/admin/notifications.php">Notifications</a>
  </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
"""

ADMIN_SETTINGS_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$biz = get_option('business');
$theme = get_option('theme');
$flight = get_option('flight');
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-3">General Settings</h3>
  <form method="post" action="/admin/save_settings.php" class="row g-4">
    <input type="hidden" name="section" value="general" />
    <div class="col-md-6">
      <label class="form-label">Business Name</label>
      <input class="form-control" name="name" value="<?php echo e($biz['name']); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input class="form-control" name="email" value="<?php echo e($biz['email']); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Phone</label>
      <input class="form-control" name="phone" value="<?php echo e($biz['phone']); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Address</label>
      <input class="form-control" name="address" value="<?php echo e($biz['address']); ?>" />
    </div>

    <h5 class="mt-4">Theme</h5>
    <div class="col-md-3">
      <label class="form-label">Primary</label>
      <input type="color" class="form-control form-control-color" name="primary" value="<?php echo e($theme['primary']); ?>" />
    </div>
    <div class="col-md-3">
      <label class="form-label">Secondary</label>
      <input type="color" class="form-control form-control-color" name="secondary" value="<?php echo e($theme['secondary']); ?>" />
    </div>
    <div class="col-md-3">
      <label class="form-label">Accent</label>
      <input type="color" class="form-control form-control-color" name="accent" value="<?php echo e($theme['accent']); ?>" />
    </div>
    <div class="col-md-3">
      <label class="form-label">BG Light</label>
      <input type="color" class="form-control form-control-color" name="bg_light" value="<?php echo e($theme['bg_light']); ?>" />
    </div>

    <h5 class="mt-4">Flight Search</h5>
    <div class="col-md-4">
      <label class="form-label">Provider</label>
      <select class="form-select" name="provider">
        <option value="mock" <?php if($flight['provider']==='mock') echo 'selected'; ?>>Mock</option>
        <option value="aviationstack">Aviationstack</option>
        <option value="opensky">OpenSky Network</option>
        <option value="amadeus">Amadeus</option>
        <option value="flightlabs">FlightLabs</option>
        <option value="skyscanner">Skyscanner</option>
        <option value="flightapi">FlightAPI</option>
        <option value="serpapi">SerpApi</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">API Key</label>
      <input class="form-control" name="api_key" value="<?php echo e($flight['api_key']); ?>" />
    </div>

    <div class="col-12">
      <button class="btn btn-gold">Save</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
"""

ADMIN_CONTENT_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$opts = read_options();
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title">Content Management</h3>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="about" />
    <div class="mb-2"><label class="form-label">About Us</label></div>
    <textarea class="form-control" name="about" rows="5"><?php echo e($opts['content']['about']); ?></textarea>
    <button class="btn btn-gold mt-2">Save About</button>
  </form>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="faq" />
    <div class="mb-2"><label class="form-label">FAQ (JSON Array)</label></div>
    <textarea class="form-control" name="faq" rows="8"><?php echo e(json_encode($opts['content']['faq'], JSON_PRETTY_PRINT)); ?></textarea>
    <button class="btn btn-gold mt-2">Save FAQ</button>
  </form>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="testimonials" />
    <div class="mb-2"><label class="form-label">Testimonials (JSON Array)</label></div>
    <textarea class="form-control" name="testimonials" rows="8"><?php echo e(json_encode($opts['content']['testimonials'], JSON_PRETTY_PRINT)); ?></textarea>
    <button class="btn btn-gold mt-2">Save Testimonials</button>
  </form>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="destinations" />
    <div class="mb-2"><label class="form-label">Featured Destinations (JSON Array)</label></div>
    <textarea class="form-control" name="destinations" rows="8"><?php echo e(json_encode($opts['content']['destinations'], JSON_PRETTY_PRINT)); ?></textarea>
    <button class="btn btn-gold mt-2">Save Destinations</button>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
"""

ADMIN_NOTIFICATIONS_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$notifications = get_option('notifications');
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title">Notifications</h3>
  <form method="post" action="/admin/save_settings.php" class="row g-3" style="max-width: 680px;">
    <input type="hidden" name="section" value="notifications" />
    <div class="col-md-8">
      <label class="form-label">Admin Email</label>
      <input class="form-control" name="email" value="<?php echo e($notifications['email']); ?>" />
    </div>
    <div class="col-md-4">
      <label class="form-label">SMS Number</label>
      <input class="form-control" name="sms" value="<?php echo e($notifications['sms']); ?>" placeholder="optional" />
    </div>
    <div class="col-12">
      <button class="btn btn-gold">Save</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
"""

ADMIN_SAVE_SETTINGS_PHP = """
<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

if (!is_post()) { redirect('/admin/dashboard.php'); }

$section = $_POST['section'] ?? '';
$opts = read_options();

switch ($section) {
  case 'general':
    $opts['business']['name'] = $_POST['name'] ?? $opts['business']['name'];
    $opts['business']['email'] = $_POST['email'] ?? $opts['business']['email'];
    $opts['business']['phone'] = $_POST['phone'] ?? $opts['business']['phone'];
    $opts['business']['address'] = $_POST['address'] ?? $opts['business']['address'];
    $opts['theme']['primary'] = $_POST['primary'] ?? $opts['theme']['primary'];
    $opts['theme']['secondary'] = $_POST['secondary'] ?? $opts['theme']['secondary'];
    $opts['theme']['accent'] = $_POST['accent'] ?? $opts['theme']['accent'];
    $opts['theme']['bg_light'] = $_POST['bg_light'] ?? $opts['theme']['bg_light'];
    break;
  case 'faq':
    $data = json_decode($_POST['faq'] ?? '[]', true);
    if (is_array($data)) { $opts['content']['faq'] = $data; }
    break;
  case 'testimonials':
    $data = json_decode($_POST['testimonials'] ?? '[]', true);
    if (is_array($data)) { $opts['content']['testimonials'] = $data; }
    break;
  case 'destinations':
    $data = json_decode($_POST['destinations'] ?? '[]', true);
    if (is_array($data)) { $opts['content']['destinations'] = $data; }
    break;
  case 'notifications':
    $opts['notifications']['email'] = $_POST['email'] ?? $opts['notifications']['email'];
    $opts['notifications']['sms'] = $_POST['sms'] ?? $opts['notifications']['sms'];
    break;
}

save_options($opts);
redirect('/admin/dashboard.php');
"""

DB_SCHEMA_SQL = """
-- Optional MySQL schema; JSON settings are used by default.
CREATE TABLE IF NOT EXISTS settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(190) NOT NULL UNIQUE,
  `value` JSON NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
"""

THANKYOU_LOGO_SVG = svg_placeholder(200, 200, "FTT", COLORS['primary'], COLORS['accent'])
FAVICON_SVG = svg_placeholder(64, 64, "FT", COLORS['primary'], COLORS['secondary'])
HERO_SVG = svg_placeholder(1600, 900, "Explore the World", COLORS['primary'], COLORS['accent'])
TEAM_SVG = svg_placeholder(1200, 800, "Our Team", COLORS['accent'], COLORS['primary'])
HOTEL_SVG = svg_placeholder(1200, 800, "Hotel Stays", COLORS['secondary'], COLORS['accent'])
DEST_LAGOS_SVG = svg_placeholder(1200, 800, "Lagos", COLORS['primary'], COLORS['secondary'])
DEST_LONDON_SVG = svg_placeholder(1200, 800, "London", COLORS['secondary'], COLORS['accent'])
DEST_DUBAI_SVG = svg_placeholder(1200, 800, "Dubai", COLORS['primary'], COLORS['accent'])
DEST_NAIROBI_SVG = svg_placeholder(1200, 800, "Nairobi", COLORS['accent'], COLORS['secondary'])

THANKYOU_PHP_PATH = PROJECT_ROOT / "thankyou.php"

PAGES = {
    PROJECT_ROOT / "index.php": INDEX_PHP,
    PROJECT_ROOT / "about.php": ABOUT_PHP,
    PROJECT_ROOT / "services.php": SERVICES_PHP,
    PROJECT_ROOT / "faq.php": FAQ_PHP,
    PROJECT_ROOT / "testimonials.php": TESTIMONIALS_PHP,
    PROJECT_ROOT / "contact.php": CONTACT_PHP,
    PROJECT_ROOT / "search.php": SEARCH_PHP,
    PROJECT_ROOT / "book.php": BOOK_PHP,
    THANKYOU_PHP_PATH: THANKYOU_PHP,
}

PARTIALS = {
    PROJECT_ROOT / "partials/head.php": PARTIALS_HEAD_PHP,
    PROJECT_ROOT / "partials/navbar.php": PARTIALS_NAVBAR_PHP,
    PROJECT_ROOT / "partials/footer.php": PARTIALS_FOOTER_PHP,
}

SERVICES = {
    PROJECT_ROOT / "services/helpers.php": SERVICES_HELPERS_PHP,
    PROJECT_ROOT / "services/mailer.php": SERVICES_MAILER_PHP,
    PROJECT_ROOT / "services/flight_api.php": SERVICES_FLIGHT_API_PHP,
}

CONFIG = {
    PROJECT_ROOT / "config/db.php": CONFIG_DB_PHP,
    PROJECT_ROOT / "config/config.php": CONFIG_CONFIG_PHP,
}

ADMIN = {
    PROJECT_ROOT / "admin/login.php": ADMIN_LOGIN_PHP,
    PROJECT_ROOT / "admin/dashboard.php": ADMIN_DASHBOARD_PHP,
    PROJECT_ROOT / "admin/settings.php": ADMIN_SETTINGS_PHP,
    PROJECT_ROOT / "admin/content.php": ADMIN_CONTENT_PHP,
    PROJECT_ROOT / "admin/notifications.php": ADMIN_NOTIFICATIONS_PHP,
    PROJECT_ROOT / "admin/save_settings.php": ADMIN_SAVE_SETTINGS_PHP,
}

HANDLERS = {
    PROJECT_ROOT / "handlers/submit_booking.php": HANDLER_BOOKING_PHP,
    PROJECT_ROOT / "handlers/submit_contact.php": HANDLER_CONTACT_PHP,
}

ASSETS = {
    PROJECT_ROOT / "assets/css/style.css": ASSETS_CSS,
    PROJECT_ROOT / "assets/js/main.js": ASSETS_JS,
    PROJECT_ROOT / "assets/images/logo.svg": THANKYOU_LOGO_SVG,
    PROJECT_ROOT / "assets/images/favicon.svg": FAVICON_SVG,
    PROJECT_ROOT / "assets/images/hero.svg": HERO_SVG,
    PROJECT_ROOT / "assets/images/team.svg": TEAM_SVG,
    PROJECT_ROOT / "assets/images/hotel.svg": HOTEL_SVG,
    PROJECT_ROOT / "assets/images/destination_lagos.svg": DEST_LAGOS_SVG,
    PROJECT_ROOT / "assets/images/destination_london.svg": DEST_LONDON_SVG,
    PROJECT_ROOT / "assets/images/destination_dubai.svg": DEST_DUBAI_SVG,
    PROJECT_ROOT / "assets/images/destination_nairobi.svg": DEST_NAIROBI_SVG,
}

MISC = {
    PROJECT_ROOT / "README.md": README_MD,
    PROJECT_ROOT / ".env.example": ENV_EXAMPLE,
    PROJECT_ROOT / "database/schema.sql": DB_SCHEMA_SQL,
}


def main():
    ensure_dir(PROJECT_ROOT)
    # Directories that must exist
    for d in [
        PROJECT_ROOT / "partials",
        PROJECT_ROOT / "services",
        PROJECT_ROOT / "config",
        PROJECT_ROOT / "admin",
        PROJECT_ROOT / "handlers",
        PROJECT_ROOT / "assets/css",
        PROJECT_ROOT / "assets/js",
        PROJECT_ROOT / "assets/images",
        PROJECT_ROOT / "storage/logs",
        PROJECT_ROOT / "storage/requests",
        PROJECT_ROOT / "database",
    ]:
        ensure_dir(d)

    for path, content in {**PAGES, **PARTIALS, **SERVICES, **CONFIG, **ADMIN, **HANDLERS, **ASSETS, **MISC}.items():
        write_file(path, content)

    print("\nDone. Next steps:")
    print(f"- Serve: php -S localhost:8000 -t {PROJECT_ROOT}")
    print("- Admin: http://localhost:8000/admin/login.php (admin@local / admin123)")


if __name__ == "__main__":
    main()