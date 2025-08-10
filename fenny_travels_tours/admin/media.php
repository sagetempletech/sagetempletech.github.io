<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$media = get_option('media');
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-3">Media</h3>
  <p class="text-muted">Upload and update site images. Accepted: PNG, JPG, SVG (max ~5MB).</p>
  <form method="post" action="/admin/save_media.php" enctype="multipart/form-data" class="row g-4">
    <div class="col-md-6">
      <label class="form-label">Logo</label>
      <input type="file" class="form-control" name="logo" accept="image/*" />
      <div class="small mt-1">Current: <code><?php echo e($media['logo'] ?? ''); ?></code></div>
    </div>
    <div class="col-md-6">
      <label class="form-label">Favicon</label>
      <input type="file" class="form-control" name="favicon" accept="image/*" />
      <div class="small mt-1">Current: <code><?php echo e($media['favicon'] ?? ''); ?></code></div>
    </div>
    <div class="col-md-6">
      <label class="form-label">Hero Background</label>
      <input type="file" class="form-control" name="hero" accept="image/*" />
      <div class="small mt-1">Current: <code><?php echo e($media['hero'] ?? ''); ?></code></div>
    </div>
    <div class="col-md-6">
      <label class="form-label">Team Image</label>
      <input type="file" class="form-control" name="team" accept="image/*" />
      <div class="small mt-1">Current: <code><?php echo e($media['team'] ?? ''); ?></code></div>
    </div>
    <div class="col-md-6">
      <label class="form-label">Hotel Image</label>
      <input type="file" class="form-control" name="hotel" accept="image/*" />
      <div class="small mt-1">Current: <code><?php echo e($media['hotel'] ?? ''); ?></code></div>
    </div>
    <div class="col-12">
      <button class="btn btn-gold">Upload & Save</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>