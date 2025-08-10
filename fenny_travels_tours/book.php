<?php
require_once __DIR__ . '/services/helpers.php';
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <h3 class="section-title">Booking Request</h3>
  <p class="text-muted">Provide your details and preferences. We will contact you to finalize your booking.</p>
  <form action="/handlers/submit_booking.php" method="post" class="row g-3">
    <input type="hidden" name="flight_id" value="<?php echo e($_GET['id'] ?? ''); ?>" />
    <input type="hidden" name="summary" value="<?php echo e(($_GET['carrier'] ?? '') . ' ' . ($_GET['from'] ?? '') . '→' . ($_GET['to'] ?? '') . ' ' . ($_GET['date'] ?? '')); ?>" />
    <div class="col-md-6">
      <label class="form-label">Full Name</label>
      <input class="form-control" name="name" required />
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required />
    </div>
    <div class="col-md-6">
      <label class="form-label">Phone</label>
      <input class="form-control" name="phone" required />
    </div>
    <div class="col-md-6">
      <label class="form-label">Preferred Cabin</label>
      <select class="form-select" name="cabin">
        <option>Economy</option>
        <option>Business</option>
        <option>First</option>
      </select>
    </div>
    <div class="col-12">
      <label class="form-label">Preferences / Notes</label>
      <textarea class="form-control" name="notes" rows="4" placeholder="Seat preference, meal, assistance..."></textarea>
    </div>
    <div class="col-12">
      <button class="btn btn-gold">Submit Request</button>
    </div>
  </form>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
