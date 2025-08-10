<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

$opts = read_options();
$services = $opts['content']['services'] ?? [];

if (is_post()) {
    if (isset($_POST['delete_index'])) {
        $idx = (int) $_POST['delete_index'];
        if (isset($services[$idx])) { array_splice($services, $idx, 1); }
    } else {
        $title = trim($_POST['title'] ?? '');
        $desc = trim($_POST['desc'] ?? '');
        if ($title && $desc) { $services[] = ['title' => $title, 'desc' => $desc]; }
    }
    $opts['content']['services'] = array_values($services);
    save_options($opts);
}

redirect('/admin/services_manage.php');