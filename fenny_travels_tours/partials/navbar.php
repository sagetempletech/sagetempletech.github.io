<?php $biz = get_option('business'); $media = get_option('media'); $logoH = (int)($media['logo_height'] ?? 36); if ($logoH < 20) { $logoH = 20; } if ($logoH > 96) { $logoH = 96; } ?>
<nav id="mainNav" class="navbar navbar-expand-lg navbar-dark fixed-top nav-glass">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/index.php">
      <img src="<?php echo e($media['logo'] ?? '/assets/images/logo.svg'); ?>" alt="Logo" height="<?php echo $logoH; ?>" class="me-2" />
      <?php echo e($biz['name']); ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="/faq.php">FAQ</a></li>
        <li class="nav-item"><a class="nav-link" href="/testimonials.php">Testimonials</a></li>
        <li class="nav-item"><a class="nav-link" href="/contact.php">Contact</a></li>
        <li class="nav-item ms-lg-2"><a class="btn btn-gold btn-sm px-3" href="/index.php#search">Book Now</a></li>
      </ul>
    </div>
  </div>
</nav>
