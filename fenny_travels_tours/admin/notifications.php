<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$notifications = get_option('notifications');
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title">Notifications</h3>
  <form method="post" action="/admin/save_settings.php" class="row g-3" style="max-width: 680px;">
    <input type="hidden" name="section" value="notifications" />
    <div class="col-md-8">
      <label class="form-label">Admin Email</label>
      <input class="form-control" name="email" value="<?php echo e($notifications['email']); ?>" />
    </div>
    <div class="col-md-4">
      <label class="form-label">SMS Number</label>
      <input class="form-control" name="sms" value="<?php echo e($notifications['sms']); ?>" placeholder="optional" />
    </div>
    <div class="col-12">
      <button class="btn btn-gold">Save</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
