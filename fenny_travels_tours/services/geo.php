<?php
require_once __DIR__ . '/helpers.php';

function geo_get_client_ip(): string {
    $keys = ['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_X_REAL_IP','REMOTE_ADDR'];
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $v = $_SERVER[$k];
            if ($k === 'HTTP_X_FORWARDED_FOR') {
                $parts = explode(',', $v);
                $v = trim($parts[0] ?? '');
            }
            if (filter_var($v, FILTER_VALIDATE_IP)) { return $v; }
        }
    }
    return '127.0.0.1';
}

function geo_detect_country_code(string $ip): ?string {
    // Cache for 24h
    $cacheKey = 'geo_' . md5($ip);
    $cachePath = storage_path('logs/' . $cacheKey . '.json');
    if (file_exists($cachePath) && (time() - filemtime($cachePath) < 86400)) {
        $data = json_decode(@file_get_contents($cachePath), true);
        if (!empty($data['country_code'])) { return $data['country_code']; }
    }
    $endpoints = [
        'https://ipapi.co/' . urlencode($ip) . '/json/',
        'https://ipwho.is/' . urlencode($ip),
    ];
    foreach ($endpoints as $url) {
        $resp = @file_get_contents($url);
        if ($resp) {
            $json = json_decode($resp, true);
            $cc = $json['country_code'] ?? ($json['country_code_iso3'] ?? ($json['country'] ?? null));
            if ($cc && strlen($cc) >= 2) {
                @file_put_contents($cachePath, json_encode(['country_code' => strtoupper(substr($cc,0,2))]));
                return strtoupper(substr($cc,0,2));
            }
        }
    }
    return null;
}

function geo_guess_currency_by_country(?string $cc): string {
    $map = [
        'NG' => 'NGN', 'GH' => 'GHS', 'KE' => 'KES', 'ZA' => 'ZAR',
        'US' => 'USD', 'GB' => 'GBP', 'EU' => 'EUR', 'AE' => 'AED',
        'CA' => 'CAD', 'AU' => 'AUD', 'IN' => 'INR'
    ];
    if ($cc && isset($map[$cc])) return $map[$cc];
    return get_option('currency.fallback', 'USD');
}