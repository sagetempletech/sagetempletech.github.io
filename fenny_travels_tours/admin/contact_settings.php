<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$biz = get_option('business');
$contact = get_option('content.contact', ['map_embed' => '', 'social' => []]);
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-3">Contact Page</h3>
  <form method="post" action="/admin/save_contact_settings.php" class="row g-4">
    <div class="col-md-6">
      <label class="form-label">Business Email</label>
      <input class="form-control" name="biz_email" value="<?php echo e($biz['email']); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Business Phone</label>
      <input class="form-control" name="biz_phone" value="<?php echo e($biz['phone']); ?>" />
    </div>
    <div class="col-12">
      <label class="form-label">Business Address</label>
      <input class="form-control" name="biz_address" value="<?php echo e($biz['address']); ?>" />
    </div>
    <div class="col-12">
      <label class="form-label">Google Map Embed URL</label>
      <input class="form-control" name="map_embed" value="<?php echo e($contact['map_embed'] ?? ''); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Facebook URL</label>
      <input class="form-control" name="facebook" value="<?php echo e(($contact['social']['facebook'] ?? '')); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Instagram URL</label>
      <input class="form-control" name="instagram" value="<?php echo e(($contact['social']['instagram'] ?? '')); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Twitter URL</label>
      <input class="form-control" name="twitter" value="<?php echo e(($contact['social']['twitter'] ?? '')); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">LinkedIn URL</label>
      <input class="form-control" name="linkedin" value="<?php echo e(($contact['social']['linkedin'] ?? '')); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">WhatsApp URL</label>
      <input class="form-control" name="whatsapp" value="<?php echo e(($contact['social']['whatsapp'] ?? '')); ?>" />
    </div>
    <div class="col-12">
      <button class="btn btn-gold">Save</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>