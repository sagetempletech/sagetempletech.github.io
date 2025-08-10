<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$biz = get_option('business');
$theme = get_option('theme');
$flight = get_option('flight');
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-3">General Settings</h3>
  <form method="post" action="/admin/save_settings.php" class="row g-4">
    <input type="hidden" name="section" value="general" />
    <div class="col-md-6">
      <label class="form-label">Business Name</label>
      <input class="form-control" name="name" value="<?php echo e($biz['name']); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input class="form-control" name="email" value="<?php echo e($biz['email']); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Phone</label>
      <input class="form-control" name="phone" value="<?php echo e($biz['phone']); ?>" />
    </div>
    <div class="col-md-6">
      <label class="form-label">Address</label>
      <input class="form-control" name="address" value="<?php echo e($biz['address']); ?>" />
    </div>

    <h5 class="mt-4">Theme</h5>
    <div class="col-md-3">
      <label class="form-label">Primary</label>
      <input type="color" class="form-control form-control-color" name="primary" value="<?php echo e($theme['primary']); ?>" />
    </div>
    <div class="col-md-3">
      <label class="form-label">Secondary</label>
      <input type="color" class="form-control form-control-color" name="secondary" value="<?php echo e($theme['secondary']); ?>" />
    </div>
    <div class="col-md-3">
      <label class="form-label">Accent</label>
      <input type="color" class="form-control form-control-color" name="accent" value="<?php echo e($theme['accent']); ?>" />
    </div>
    <div class="col-md-3">
      <label class="form-label">BG Light</label>
      <input type="color" class="form-control form-control-color" name="bg_light" value="<?php echo e($theme['bg_light']); ?>" />
    </div>

    <h5 class="mt-4">Flight Search</h5>
    <div class="col-md-4">
      <label class="form-label">Provider</label>
      <select class="form-select" name="provider">
        <option value="mock" <?php if($flight['provider']==='mock') echo 'selected'; ?>>Mock</option>
        <option value="aviationstack">Aviationstack</option>
        <option value="opensky">OpenSky Network</option>
        <option value="amadeus">Amadeus</option>
        <option value="flightlabs">FlightLabs</option>
        <option value="skyscanner">Skyscanner</option>
        <option value="flightapi">FlightAPI</option>
        <option value="serpapi">SerpApi</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">API Key</label>
      <input class="form-control" name="api_key" value="<?php echo e($flight['api_key']); ?>" />
    </div>

    <div class="col-12">
      <button class="btn btn-gold">Save</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
