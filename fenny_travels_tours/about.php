<?php
require_once __DIR__ . '/services/helpers.php';
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-lg-6" data-aos="fade-right">
      <h2 class="section-title">About Fenny Travels & Tours</h2>
      <p><?php echo e(get_option('content.about')); ?></p>
      <div class="mt-3">
        <div class="neu p-3 d-inline-block me-3">Trusted Advisors</div>
        <div class="neu p-3 d-inline-block">Global Network</div>
      </div>
    </div>
    <div class="col-lg-6" data-aos="fade-left">
      <img src="/assets/images/team.svg" alt="Team" class="w-100 rounded-4 shadow" />
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
