<?php
require_once __DIR__ . '/services/helpers.php';
$testimonials = get_option('content.testimonials', []);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="text-center mb-4">
    <h2 class="section-title">Testimonials</h2>
    <p class="text-muted">User-submitted experiences</p>
  </div>
  <div class="row g-4">
    <?php foreach ($testimonials as $t): ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="testimonial glow-hover h-100">
          <div class="rating mb-2" data-stars="<?php echo (int)$t['rating']; ?>"></div>
          <div class="fw-bold mb-1"><?php echo e($t['name']); ?></div>
          <div>"<?php echo e($t['text']); ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
