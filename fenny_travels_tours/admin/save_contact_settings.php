<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

if (!is_post()) { redirect('/admin/contact_settings.php'); }

$opts = read_options();

$opts['business']['email'] = $_POST['biz_email'] ?? $opts['business']['email'];
$opts['business']['phone'] = $_POST['biz_phone'] ?? $opts['business']['phone'];
$opts['business']['address'] = $_POST['biz_address'] ?? $opts['business']['address'];

$contact = $opts['content']['contact'] ?? ['map_embed' => '', 'social' => []];
$contact['map_embed'] = $_POST['map_embed'] ?? ($contact['map_embed'] ?? '');
$contact['social']['facebook'] = $_POST['facebook'] ?? ($contact['social']['facebook'] ?? '');
$contact['social']['instagram'] = $_POST['instagram'] ?? ($contact['social']['instagram'] ?? '');
$contact['social']['twitter'] = $_POST['twitter'] ?? ($contact['social']['twitter'] ?? '');
$contact['social']['linkedin'] = $_POST['linkedin'] ?? ($contact['social']['linkedin'] ?? '');
$contact['social']['whatsapp'] = $_POST['whatsapp'] ?? ($contact['social']['whatsapp'] ?? '');

$opts['content']['contact'] = $contact;
save_options($opts);
redirect('/admin/contact_settings.php');