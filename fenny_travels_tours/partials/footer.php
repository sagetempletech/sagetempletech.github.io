<?php $biz = get_option('business'); ?>
<footer class="pt-5 pb-4 mt-5 footer-glass">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6">
        <h5 class="fw-bold">Fenny Travels & Tours</h5>
        <p class="mb-2"><span class="text-muted">Address:</span> <?php echo e($biz['address']); ?></p>
        <p class="mb-2"><span class="text-muted">Phone/WhatsApp:</span> <?php echo e($biz['phone']); ?></p>
        <p class="mb-0"><span class="text-muted">Email:</span> <?php echo e($biz['email']); ?></p>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold">Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="/about.php" class="link-light">About</a></li>
          <li><a href="/services.php" class="link-light">Services</a></li>
          <li><a href="/faq.php" class="link-light">FAQ</a></li>
          <li><a href="/testimonials.php" class="link-light">Testimonials</a></li>
          <li><a href="/contact.php" class="link-light">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold">Follow Us</h6>
        <div class="d-flex gap-3">
          <a class="social glow" href="#" aria-label="Facebook"><span class="bi bi-facebook"></span></a>
          <a class="social glow" href="#" aria-label="Instagram"><span class="bi bi-instagram"></span></a>
          <a class="social glow" href="#" aria-label="Twitter"><span class="bi bi-twitter"></span></a>
          <a class="social glow" href="#" aria-label="LinkedIn"><span class="bi bi-linkedin"></span></a>
        </div>
      </div>
    </div>
    <div class="text-center mt-4 small opacity-75">&copy; <?php echo date('Y'); ?> Fenny Travels & Tours · All rights reserved.</div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
<script src="/assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
