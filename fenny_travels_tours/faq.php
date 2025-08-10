<?php
require_once __DIR__ . '/services/helpers.php';
$faqs = get_option('content.faq', []);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <h2 class="section-title mb-4">Frequently Asked Questions</h2>
  <div class="accordion" id="faqAccordion">
    <?php foreach ($faqs as $i => $f): $id = 'fq' . $i; ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="h<?php echo $id; ?>">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c<?php echo $id; ?>" aria-expanded="false" aria-controls="c<?php echo $id; ?>">
            <?php echo e($f['q']); ?>
          </button>
        </h2>
        <div id="c<?php echo $id; ?>" class="accordion-collapse collapse" aria-labelledby="h<?php echo $id; ?>" data-bs-parent="#faqAccordion">
          <div class="accordion-body"><?php echo e($f['a']); ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
