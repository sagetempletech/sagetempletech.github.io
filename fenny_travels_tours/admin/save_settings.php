<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

if (!is_post()) { redirect('/admin/settings.php'); }

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
    $opts['theme']['mode'] = $_POST['mode'] ?? ($opts['theme']['mode'] ?? 'light');
    $opts['theme']['heading_font'] = $_POST['heading_font'] ?? $opts['theme']['heading_font'];
    $opts['theme']['body_font'] = $_POST['body_font'] ?? $opts['theme']['body_font'];
    // Flight provider + API key
    $opts['flight']['provider'] = $_POST['provider'] ?? $opts['flight']['provider'];
    $opts['flight']['api_key'] = $_POST['api_key'] ?? $opts['flight']['api_key'];
    break;
  case 'home':
    $opts['content']['home_hero_title'] = $_POST['home_hero_title'] ?? $opts['content']['home_hero_title'];
    $opts['content']['home_hero_subtitle'] = $_POST['home_hero_subtitle'] ?? $opts['content']['home_hero_subtitle'];
    break;
  case 'about':
    $opts['content']['about'] = $_POST['about'] ?? $opts['content']['about'];
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

// Redirect to the appropriate settings screen with success flag
switch ($section) {
  case 'general':
    redirect('/admin/settings.php?success=1');
  case 'home':
  case 'about':
  case 'faq':
  case 'testimonials':
  case 'destinations':
    redirect('/admin/content.php?success=1');
  case 'notifications':
    redirect('/admin/notifications.php?success=1');
  default:
    redirect($_SERVER['HTTP_REFERER'] ?? '/admin/dashboard.php');
}
