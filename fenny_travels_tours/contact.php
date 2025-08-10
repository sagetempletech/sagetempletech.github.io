<?php
require_once __DIR__ . '/services/helpers.php';
$biz = get_option('business');
$contact = get_option('content.contact', ['map_embed' => '', 'social' => []]);
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
          src="<?php echo e($contact['map_embed'] ?? ''); ?>"
          style="border:0; filter: hue-rotate(330deg) saturate(1.2);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="mt-3 small">
        <div><strong>Address:</strong> <?php echo e($biz['address']); ?></div>
        <div><strong>Phone/WhatsApp:</strong> <?php echo e($biz['phone']); ?></div>
        <div><strong>Email:</strong> <?php echo e($biz['email']); ?></div>
      </div>
      <div class="mt-3 d-flex gap-3">
        <?php $social = $contact['social'] ?? []; ?>
        <?php if (!empty($social['facebook'])): ?><a class="social glow" href="<?php echo e($social['facebook']); ?>" aria-label="Facebook"><span class="bi bi-facebook"></span></a><?php endif; ?>
        <?php if (!empty($social['instagram'])): ?><a class="social glow" href="<?php echo e($social['instagram']); ?>" aria-label="Instagram"><span class="bi bi-instagram"></span></a><?php endif; ?>
        <?php if (!empty($social['twitter'])): ?><a class="social glow" href="<?php echo e($social['twitter']); ?>" aria-label="Twitter"><span class="bi bi-twitter"></span></a><?php endif; ?>
        <?php if (!empty($social['linkedin'])): ?><a class="social glow" href="<?php echo e($social['linkedin']); ?>" aria-label="LinkedIn"><span class="bi bi-linkedin"></span></a><?php endif; ?>
        <?php if (!empty($social['whatsapp'])): ?><a class="social glow" href="<?php echo e($social['whatsapp']); ?>" aria-label="WhatsApp"><span class="bi bi-whatsapp"></span></a><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
