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
