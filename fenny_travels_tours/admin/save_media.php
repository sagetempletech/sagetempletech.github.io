<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

if (!is_post()) { redirect('/admin/media.php'); }

$opts = read_options();
$media = $opts['media'] ?? [];

$uploadDir = dirname(__DIR__) . '/assets/images/uploads';
if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }

function handle_upload(string $key, array &$media, string $uploadDir): void {
    if (!isset($_FILES[$key]) || ($_FILES[$key]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) { return; }
    $tmp = $_FILES[$key]['tmp_name'];
    $name = basename($_FILES[$key]['name']);
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $allowed = ['png','jpg','jpeg','svg','webp'];
    if (!in_array($ext, $allowed, true)) { return; }
    $safe = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', strtolower($name));
    $destName = uniqid($key . '_') . '_' . $safe;
    $destPath = rtrim($uploadDir, '/\\') . '/' . $destName;
    if (@move_uploaded_file($tmp, $destPath)) {
        $media[$key] = '/assets/images/uploads/' . $destName;
    }
}

foreach (['logo','favicon','hero','team','hotel'] as $k) {
    handle_upload($k, $media, $uploadDir);
}

if (isset($_POST['logo_height'])) {
    $h = (int) $_POST['logo_height'];
    if ($h < 20) { $h = 20; }
    if ($h > 96) { $h = 96; }
    $media['logo_height'] = $h;
}

$opts['media'] = $media;
save_options($opts);
redirect('/admin/media.php');