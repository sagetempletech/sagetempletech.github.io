<?php
require_once __DIR__ . '/services/helpers.php';
require_once __DIR__ . '/services/flight_api.php';
$heroTitle = get_option('content.home_hero_title', 'Fly Smarter with <span style="color: var(--secondary);">Fenny</span> — Your Journey Starts Here');
$heroSubtitle = get_option('content.home_hero_subtitle', 'Domestic & international flights, hotels, visas and travel insurance backed by real experts.');
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<header class="hero">
  <div class="hero-bg" aria-hidden="true"></div>
  <div class="overlay" aria-hidden="true"></div>
  <div class="content container" data-aos="fade-up">
    <span class="badge badge-glass mb-3 px-3 py-2 fw-semibold">Seamless · Enjoyable · Affordable</span>
    <h1><?php echo $heroTitle; ?></h1>
    <p class="lead mt-2"><?php echo e($heroSubtitle); ?></p>
    <a href="#search" class="btn btn-gold btn-lg mt-3 glow-hover">Search Flights</a>
  </div>
</header>

<section id="search" class="container position-relative">
  <div class="card card-glass p-4 p-md-5 search-widget" data-aos="fade-up">
    <form class="row g-3" action="/search.php" method="get">
      <div class="col-12 col-md-3">
        <label class="form-label fw-semibold">Trip Type</label>
        <select name="trip" class="form-select">
          <option value="round">Round Trip</option>
          <option value="oneway">One-way</option>
          <option value="multicity">Multi-city</option>
        </select>
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">From</label>
        <input type="text" class="form-control" name="from" placeholder="ABV" required />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">To</label>
        <input type="text" class="form-control" name="to" placeholder="LOS" required />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Depart</label>
        <input type="date" class="form-control" name="depart_date" required />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Return</label>
        <input type="date" class="form-control" name="return_date" />
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Cabin</label>
        <select class="form-select" name="cabin">
          <option value="economy">Economy</option>
          <option value="business">Business</option>
          <option value="first">First Class</option>
        </select>
      </div>
      <div class="col-4 col-md-2">
        <label class="form-label fw-semibold">Adults</label>
        <input type="number" min="1" value="1" class="form-control" name="adults" />
      </div>
      <div class="col-4 col-md-2">
        <label class="form-label fw-semibold">Children</label>
        <input type="number" min="0" value="0" class="form-control" name="children" />
      </div>
      <div class="col-4 col-md-2">
        <label class="form-label fw-semibold">Infants</label>
        <input type="number" min="0" value="0" class="form-control" name="infants" />
      </div>
      <div class="col-12 col-md-2 d-grid">
        <label class="form-label opacity-0">&nbsp;</label>
        <button class="btn btn-gold btn-lg">Search</button>
      </div>
    </form>
  </div>
</section>

<section class="container py-6">
  <div class="text-center mb-4" data-aos="fade-up">
    <h2 class="section-title">Featured Destinations</h2>
    <p class="text-muted">Curated deals handpicked by our travel consultants</p>
  </div>
  <div class="row g-4">
    <?php foreach (get_option('content.destinations', []) as $d): ?>
      <div class="col-12 col-sm-6 col-lg-3" data-aos="zoom-in">
        <div class="dest-card glow-hover neu p-2 h-100">
          <img src="/<?php echo e($d['image']); ?>" class="w-100" alt="<?php echo e($d['city']); ?>" />
          <div class="p-3">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 fw-bold"><?php echo e($d['city'] . ', ' . $d['country']); ?></h5>
              <span class="badge text-bg-warning">$<?php echo e($d['price']); ?></span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-md-6" data-aos="fade-right">
      <h3 class="section-title">Why Travel with Us</h3>
      <p>With years of experience across routes worldwide, we craft journeys that are reliable, flexible, and affordable. From tickets to visas and insurance, we simplify everything so you can focus on the memories.</p>
      <ul class="list-unstyled">
        <li class="mb-2">✔ Expert travel consultants</li>
        <li class="mb-2">✔ Exclusive partner deals</li>
        <li class="mb-2">✔ End-to-end itinerary support</li>
      </ul>
    </div>
    <div class="col-md-6" data-aos="fade-left">
      <div class="row g-3 text-center">
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="8">0</div><div>Years of Service</div></div></div>
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="120">0</div><div>Destinations</div></div></div>
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="4500">0</div><div>Happy Customers</div></div></div>
        <div class="col-6"><div class="neu p-4"><div class="counter display-5" data-counter="30">0</div><div>Partner Airlines</div></div></div>
      </div>
    </div>
  </div>
</section>

<section class="container py-6">
  <div class="text-center mb-4" data-aos="fade-up">
    <h2 class="section-title">Latest Travel Deals</h2>
    <p class="text-muted">Limited-time offers — book a consultation</p>
  </div>
  <div class="row g-4">
    <?php for ($i=0;$i<6;$i++): ?>
    <div class="col-12 col-md-6 col-lg-4" data-aos="zoom-in">
      <div class="card h-100 card-glass glow-hover">
        <div class="card-body p-4">
          <span class="badge text-bg-primary mb-2">Special</span>
          <h5 class="card-title fw-bold">Flash Deal #<?php echo $i+1; ?></h5>
          <p class="card-text">Save up to 20% on select routes. Talk to our consultants for tailored, cost-effective options.</p>
          <a href="#search" class="stretched-link"></a>
        </div>
      </div>
    </div>
    <?php endfor; ?>
  </div>
</section>

<section class="container py-6">
  <div class="text-center mb-4" data-aos="fade-up">
    <h2 class="section-title">What Clients Say</h2>
    <p class="text-muted">Real feedback from happy travelers</p>
  </div>
  <div class="row g-4">
    <?php foreach (get_option('content.testimonials', []) as $t): ?>
      <div class="col-12 col-md-4" data-aos="fade-up">
        <div class="testimonial glow-hover h-100">
          <div class="rating mb-2" data-stars="<?php echo (int)$t['rating']; ?>"></div>
          <p class="mb-2">"<?php echo e($t['text']); ?>"</p>
          <div class="fw-bold">— <?php echo e($t['name']); ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
