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
