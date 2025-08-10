<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }

$requestsDir = storage_path('requests');
$files = glob($requestsDir . '/*.json');
$bookings = array_filter($files, fn($f) => str_contains($f, 'booking_'));
$contacts = array_filter($files, fn($f) => str_contains($f, 'contact_'));
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-4">Admin Dashboard</h3>
  <div class="row g-4">
    <div class="col-md-4"><div class="neu p-4 text-center"><div class="display-5 fw-bold"><?php echo count($bookings); ?></div><div>Booking Requests</div></div></div>
    <div class="col-md-4"><div class="neu p-4 text-center"><div class="display-5 fw-bold"><?php echo count($contacts); ?></div><div>Contact Messages</div></div></div>
    <div class="col-md-4"><div class="neu p-4 text-center"><div class="display-5 fw-bold">1</div><div>Active Admin</div></div></div>
  </div>
  <div class="mt-4">
    <a class="btn btn-primary me-2" href="/admin/settings.php">General Settings</a>
    <a class="btn btn-outline-primary me-2" href="/admin/content.php">Content</a>
    <a class="btn btn-outline-primary" href="/admin/notifications.php">Notifications</a>
  </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
