<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/geo.php';

function currency_symbol(string $code): string {
    $map = [
        'USD' => '$', 'NGN' => '₦', 'GBP' => '£', 'EUR' => '€', 'AED' => 'د.إ',
        'GHS' => '₵', 'KES' => 'KSh', 'ZAR' => 'R', 'CAD' => 'C$', 'AUD' => 'A$', 'INR' => '₹'
    ];
    return $map[$code] ?? $code . ' ';
}

function currency_get_target(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
    if (!empty($_SESSION['currency'])) { return $_SESSION['currency']; }
    $cfgAuto = (bool) get_option('currency.auto', true);
    $fallback = get_option('currency.fallback', 'USD');
    if ($cfgAuto) {
        $cc = geo_detect_country_code(geo_get_client_ip());
        $guess = geo_guess_currency_by_country($cc);
        $_SESSION['currency'] = $guess ?: $fallback;
    } else {
        $_SESSION['currency'] = $fallback;
    }
    return $_SESSION['currency'];
}

function currency_rates_fetch(string $base): array {
    $cachePath = storage_path('logs/rates_' . $base . '.json');
    if (file_exists($cachePath) && (time() - filemtime($cachePath) < 3600)) {
        $data = json_decode(@file_get_contents($cachePath), true);
        if (!empty($data['rates'])) { return $data['rates']; }
    }
    // Use open.er-api.com free endpoint as a simple public source
    $url = 'https://open.er-api.com/v6/latest/' . urlencode($base);
    $resp = @file_get_contents($url);
    if ($resp) {
        $json = json_decode($resp, true);
        $rates = $json['rates'] ?? [];
        if ($rates) {
            @file_put_contents($cachePath, json_encode(['rates' => $rates]));
            return $rates;
        }
    }
    return [];
}

function currency_convert_amount(float $amount, string $from, string $to): float {
    $from = strtoupper($from); $to = strtoupper($to);
    if ($from === $to) { return $amount; }
    $rates = currency_rates_fetch($from);
    $rate = $rates[$to] ?? null;
    if (!$rate) { return $amount; }
    return $amount * (float)$rate;
}

function currency_format_amount(float $amount, string $code): string {
    $symbol = currency_symbol($code);
    $val = number_format($amount, ($code === 'JPY' ? 0 : 2));
    return $symbol . $val;
}