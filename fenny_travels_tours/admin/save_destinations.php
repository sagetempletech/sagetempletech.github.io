<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

$opts = read_options();
$destinations = $opts['content']['destinations'] ?? [];

if (is_post()) {
    // Deletion
    if (isset($_POST['delete_index'])) {
        $idx = (int) $_POST['delete_index'];
        if (isset($destinations[$idx])) {
            array_splice($destinations, $idx, 1);
        }
    } else {
        // Add
        $city = trim($_POST['city'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $price = (int) ($_POST['price'] ?? 0);
        $imagePath = null;
        // Upload
        if (isset($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__) . '/assets/images/uploads';
            if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
            $name = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['png','jpg','jpeg','svg','webp'];
            if (in_array($ext, $allowed, true)) {
                $safe = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', strtolower($name));
                $destName = uniqid('dest_') . '_' . $safe;
                $destPath = rtrim($uploadDir, '/\\') . '/' . $destName;
                if (@move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
                    $imagePath = 'assets/images/uploads/' . $destName;
                }
            }
        }
        if ($city && $country) {
            $destinations[] = [
                'city' => $city,
                'country' => $country,
                'price' => $price,
                'image' => $imagePath ?: ''
            ];
        }
    }

    $opts['content']['destinations'] = array_values($destinations);
    save_options($opts);
}

redirect('/admin/destinations.php');