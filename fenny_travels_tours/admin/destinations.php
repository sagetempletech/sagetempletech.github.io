<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$destinations = get_option('content.destinations', []);
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-3">Featured Destinations</h3>
  <form class="mb-4" method="post" action="/admin/save_destinations.php" enctype="multipart/form-data">
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label">City</label>
        <input class="form-control" name="city" required />
      </div>
      <div class="col-md-3">
        <label class="form-label">Country</label>
        <input class="form-control" name="country" required />
      </div>
      <div class="col-md-2">
        <label class="form-label">Price (USD)</label>
        <input type="number" class="form-control" name="price" min="0" required />
      </div>
      <div class="col-md-3">
        <label class="form-label">Image</label>
        <input type="file" class="form-control" name="image" accept="image/*" />
      </div>
      <div class="col-md-1 d-grid">
        <button class="btn btn-gold">Add</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>#</th><th>City</th><th>Country</th><th>Price</th><th>Image</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($destinations as $i => $d): ?>
        <tr>
          <td><?php echo $i+1; ?></td>
          <td><?php echo e($d['city'] ?? ''); ?></td>
          <td><?php echo e($d['country'] ?? ''); ?></td>
          <td>$<?php echo e($d['price'] ?? 0); ?></td>
          <td><?php if (!empty($d['image'])): ?><img src="/<?php echo e($d['image']); ?>" alt="" style="height:48px" /><?php endif; ?></td>
          <td>
            <form method="post" action="/admin/save_destinations.php" class="d-inline">
              <input type="hidden" name="delete_index" value="<?php echo $i; ?>" />
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete destination?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>