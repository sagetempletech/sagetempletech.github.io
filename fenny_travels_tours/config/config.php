<?php
require_once __DIR__ . '/db.php';

function storage_dir(): string {
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) { mkdir($dir, 0775, true); }
    return $dir;
}

function options_path(): string {
    $dir = storage_dir();
    return $dir . '/settings.json';
}

function read_options(): array {
    $path = options_path();
    if (!file_exists($path)) {
        $defaults = [
            'business' => [
                'name' => 'Fenny Travels & Tours',
                'address' => '19, Durban Street, Off Ademolade Tokunbo, Wuse 2, Abuja, Nigeria',
                'phone' => '0706 054 6145',
                'email' => 'admin@example.com'
            ],
            'theme' => [
                'primary' => '#004AAD',
                'secondary' => '#FFD700',
                'accent' => '#87CEFA',
                'bg_light' => '#F5F9FF',
                'heading_font' => 'Poppins',
                'body_font' => 'Open Sans',
                'mode' => 'light'
            ],
            'flight' => [
                'provider' => 'mock',
                'api_key' => '',
                'enabled_trip_types' => ['oneway', 'round', 'multicity'],
                'enabled_classes' => ['economy', 'business', 'first']
            ],
            'notifications' => [
                'email' => 'admin@example.com',
                'sms' => ''
            ],
            'media' => [
                'logo' => '/assets/images/logo.svg',
                'favicon' => '/assets/images/favicon.svg',
                'hero' => '/assets/images/hero.svg',
                'team' => '/assets/images/team.svg',
                'hotel' => '/assets/images/hotel.svg',
                'logo_height' => 36
            ],
            'admin' => [
                'email' => 'admin@local',
                // password: admin123
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT)
            ],
            'content' => [
                'home_hero_title' => 'Fly Smarter with <span style="color: var(--secondary);">Fenny</span> — Your Journey Starts Here',
                'home_hero_subtitle' => 'Domestic & international flights, hotels, visas and travel insurance backed by real experts.',
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
                ],
                'contact' => [
                    'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.886888894606!2d7.478!3d9.0819999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0b1b5f2d2a39%3A0x0!2sWuse%202%2C%20Abuja!5e0!3m2!1sen!2sNG!4v1700000000000',
                    'social' => [
                        'facebook' => '#',
                        'instagram' => '#',
                        'twitter' => '#',
                        'linkedin' => '#',
                        'whatsapp' => '#'
                    ]
                ]
            ]
        ];
        @file_put_contents($path, json_encode($defaults, JSON_PRETTY_PRINT));
        return $defaults;
    }
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function save_options(array $data): bool {
    $path = options_path();
    return (bool) @file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function get_option(string $path, $default = null) {
    $data = read_options();
    $parts = explode('.', $path);
    foreach ($parts as $p) {
        if (!is_array($data) || !array_key_exists($p, $data)) { return $default; }
        $data = $data[$p];
    }
    return $data;
}

function set_option(string $path, $value): bool {
    $data = read_options();
    $ref =& $data;
    $parts = explode('.', $path);
    foreach ($parts as $p) {
        if (!isset($ref[$p]) || !is_array($ref[$p])) { $ref[$p] = []; }
        $ref =& $ref[$p];
    }
    $ref = $value;
    return save_options($data);
}
