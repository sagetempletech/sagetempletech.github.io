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
