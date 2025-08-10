<?php
require_once __DIR__ . '/services/helpers.php';
$services = get_option('content.services', []);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="text-center mb-4">
    <h2 class="section-title">Our Services</h2>
  </div>
  <div class="row g-4">
    <?php foreach ($services as $s): ?>
      <div class="col-12 col-md-6" data-aos="fade-up">
        <div class="card card-glass p-4 h-100">
          <h5 class="fw-bold mb-2"><?php echo e($s['title']); ?></h5>
          <p class="mb-0"><?php echo e($s['desc']); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-md-6">
      <img class="w-100 rounded-4 shadow" src="/assets/images/hotel.svg" alt="Hotel gallery" />
    </div>
    <div class="col-md-6">
      <h3 class="section-title">Hotel Reservations</h3>
      <p>Browse a curated selection across budgets — handpicked for comfort, location, and value.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
