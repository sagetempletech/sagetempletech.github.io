<?php
require_once __DIR__ . '/../services/helpers.php';
require_once __DIR__ . '/../services/mailer.php';

if (!is_post()) { redirect('/index.php'); }

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

$rec = [ 'name' => $name, 'email' => $email, 'message' => $message, 'ts' => date('c') ];
@file_put_contents(storage_path('requests/contact_' . time() . '.json'), json_encode($rec, JSON_PRETTY_PRINT));

$destEmail = get_option('notifications.email', 'admin@local');
$html = '<h3>New Contact Message</h3>'
      . '<p><strong>Name:</strong> ' . e($name) . '</p>'
      . '<p><strong>Email:</strong> ' . e($email) . '</p>'
      . '<p><strong>Message:</strong><br>' . nl2br(e($message)) . '</p>';

send_mail($destEmail, 'New Contact Message', $html);

redirect('/thankyou.php');
