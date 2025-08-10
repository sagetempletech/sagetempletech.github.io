<?php
require_once __DIR__ . '/../services/helpers.php';
session_start();

if (isset($_SESSION['admin'])) { redirect('/admin/dashboard.php'); }

$error = null;
if (is_post()) {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $admin = get_option('admin');
  if (strtolower($email) === strtolower($admin['email']) && password_verify($password, $admin['password_hash'])) {
    $_SESSION['admin'] = $email;
    redirect('/admin/dashboard.php');
  } else {
    $error = 'Invalid credentials';
  }
}
?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<section class="container py-5" style="max-width: 560px;">
  <div class="card card-glass p-4">
    <h3 class="mb-3">Admin Login</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required />
      </div>
      <button class="btn btn-gold w-100">Login</button>
    </form>
  </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
