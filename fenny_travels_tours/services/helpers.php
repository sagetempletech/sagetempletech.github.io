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
    $path = rtrim($base, '/\') . '/' . ltrim($relative, '/\');
    $dir = dirname($path);
    if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
    return $path;
}

function write_log(string $file, string $message): void {
    $path = storage_path('logs/' . $file);
    @file_put_contents($path, '[' . date('c') . "] " . $message . "
", FILE_APPEND);
}
