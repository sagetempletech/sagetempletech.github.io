<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$opts = read_options();
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title">Content Management</h3>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="about" />
    <div class="mb-2"><label class="form-label">About Us</label></div>
    <textarea class="form-control" name="about" rows="5"><?php echo e($opts['content']['about']); ?></textarea>
    <button class="btn btn-gold mt-2">Save About</button>
  </form>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="faq" />
    <div class="mb-2"><label class="form-label">FAQ (JSON Array)</label></div>
    <textarea class="form-control" name="faq" rows="8"><?php echo e(json_encode($opts['content']['faq'], JSON_PRETTY_PRINT)); ?></textarea>
    <button class="btn btn-gold mt-2">Save FAQ</button>
  </form>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="testimonials" />
    <div class="mb-2"><label class="form-label">Testimonials (JSON Array)</label></div>
    <textarea class="form-control" name="testimonials" rows="8"><?php echo e(json_encode($opts['content']['testimonials'], JSON_PRETTY_PRINT)); ?></textarea>
    <button class="btn btn-gold mt-2">Save Testimonials</button>
  </form>

  <form method="post" action="/admin/save_settings.php" class="mb-5">
    <input type="hidden" name="section" value="destinations" />
    <div class="mb-2"><label class="form-label">Featured Destinations (JSON Array)</label></div>
    <textarea class="form-control" name="destinations" rows="8"><?php echo e(json_encode($opts['content']['destinations'], JSON_PRETTY_PRINT)); ?></textarea>
    <button class="btn btn-gold mt-2">Save Destinations</button>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
