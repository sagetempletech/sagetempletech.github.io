<?php
require_once __DIR__ . '/services/helpers.php';
$biz = get_option('business');
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="row g-4">
    <div class="col-lg-6">
      <h2 class="section-title">Contact Us</h2>
      <p class="text-muted">We usually respond within a few hours.</p>
      <form class="row g-3" method="post" action="/handlers/submit_contact.php">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" required />
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" required />
        </div>
        <div class="col-12">
          <label class="form-label">Message</label>
          <textarea class="form-control" rows="5" name="message" required></textarea>
        </div>
        <div class="col-12">
          <button class="btn btn-gold">Send</button>
        </div>
      </form>
    </div>
    <div class="col-lg-6">
      <div class="ratio ratio-4x3 neu">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.886888894606!2d7.478!3d9.0819999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0b1b5f2d2a39%3A0x0!2sWuse%202%2C%20Abuja!5e0!3m2!1sen!2sNG!4v1700000000000"
          style="border:0; filter: hue-rotate(330deg) saturate(1.2);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="mt-3 small">
        <div><strong>Address:</strong> <?php echo e($biz['address']); ?></div>
        <div><strong>Phone/WhatsApp:</strong> <?php echo e($biz['phone']); ?></div>
        <div><strong>Email:</strong> <?php echo e($biz['email']); ?></div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
