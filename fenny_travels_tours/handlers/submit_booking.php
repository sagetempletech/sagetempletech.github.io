<?php
require_once __DIR__ . '/../services/helpers.php';
require_once __DIR__ . '/../services/mailer.php';

if (!is_post()) { redirect('/index.php'); }

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$cabin = trim($_POST['cabin'] ?? '');
$notes = trim($_POST['notes'] ?? '');
$summary = trim($_POST['summary'] ?? '');

$rec = [
  'name' => $name,
  'email' => $email,
  'phone' => $phone,
  'cabin' => $cabin,
  'notes' => $notes,
  'summary' => $summary,
  'ts' => date('c')
];

$destEmail = get_option('notifications.email', 'admin@local');

@file_put_contents(storage_path('requests/booking_' . time() . '.json'), json_encode($rec, JSON_PRETTY_PRINT));

$html = '<h3>New Booking Request</h3>'
      . '<p><strong>Name:</strong> ' . e($name) . '</p>'
      . '<p><strong>Email:</strong> ' . e($email) . '</p>'
      . '<p><strong>Phone:</strong> ' . e($phone) . '</p>'
      . '<p><strong>Cabin:</strong> ' . e($cabin) . '</p>'
      . '<p><strong>Flight:</strong> ' . e($summary) . '</p>'
      . '<p><strong>Notes:</strong> ' . nl2br(e($notes)) . '</p>';

send_mail($destEmail, 'New Booking Request', $html);

redirect('/thankyou.php');
