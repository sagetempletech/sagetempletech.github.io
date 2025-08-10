<?php
require_once __DIR__ . '/../services/helpers.php';
session_start(); if (!isset($_SESSION['admin'])) { header('Location: /admin/login.php'); exit; }
$services = get_option('content.services', []);
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<section class="container py-5">
  <h3 class="section-title mb-3">Services</h3>

  <form class="mb-4" method="post" action="/admin/save_services.php">
    <div class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" required />
      </div>
      <div class="col-md-7">
        <label class="form-label">Description</label>
        <input class="form-control" name="desc" required />
      </div>
      <div class="col-md-1 d-grid">
        <button class="btn btn-gold">Add</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>#</th><th>Title</th><th>Description</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($services as $i => $s): ?>
        <tr>
          <td><?php echo $i+1; ?></td>
          <td><?php echo e($s['title'] ?? ''); ?></td>
          <td><?php echo e($s['desc'] ?? ''); ?></td>
          <td class="text-nowrap">
            <form method="post" action="/admin/save_services.php" class="d-inline">
              <input type="hidden" name="delete_index" value="<?php echo $i; ?>" />
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete service?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>