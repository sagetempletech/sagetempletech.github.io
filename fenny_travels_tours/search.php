<?php
require_once __DIR__ . '/services/helpers.php';
require_once __DIR__ . '/services/flight_api.php';

$criteria = [
  'trip' => $_GET['trip'] ?? 'round',
  'from' => $_GET['from'] ?? 'ABV',
  'to' => $_GET['to'] ?? 'LOS',
  'depart_date' => $_GET['depart_date'] ?? date('Y-m-d'),
  'return_date' => $_GET['return_date'] ?? null,
  'cabin' => $_GET['cabin'] ?? 'economy',
  'adults' => (int) ($_GET['adults'] ?? 1),
  'children' => (int) ($_GET['children'] ?? 0),
  'infants' => (int) ($_GET['infants'] ?? 0),
];
$results = fetch_view_only_flights($criteria);
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<section class="container py-5">
  <div class="d-flex justify-content-between align-items-end mb-3">
    <div>
      <h3 class="section-title mb-0">Flight Results</h3>
      <div class="text-muted small">From <?php echo e(strtoupper($criteria['from'])); ?> to <?php echo e(strtoupper($criteria['to'])); ?> · <?php echo e(ucfirst($criteria['cabin'])); ?> · <?php echo e($criteria['depart_date']); ?></div>
    </div>
    <a href="/index.php#search" class="btn btn-outline-primary">Modify Search</a>
  </div>
  <div class="row g-3">
    <?php foreach ($results as $r): ?>
      <div class="col-12">
        <div class="card card-glass p-3 align-items-center d-flex flex-wrap flex-md-nowrap gap-3">
          <div class="flex-grow-1">
            <div class="fw-bold h5 mb-1"><?php echo e($r['carrier']); ?></div>
            <div class="text-muted small"><?php echo e($r['from']); ?> → <?php echo e($r['to']); ?> · <?php echo e($r['duration']); ?> · <?php echo e($r['stops']); ?> stop(s)
              · <?php echo e($r['depart_time']); ?> - <?php echo e($r['arrive_time']); ?></div>
          </div>
          <div class="text-center px-3">
            <div class="h4 mb-0">$<?php echo e($r['price']); ?></div>
            <div class="small text-muted"><?php echo e($r['currency']); ?></div>
          </div>
          <div class="ms-md-auto">
            <a href="/book.php?id=<?php echo e($r['id']); ?>&from=<?php echo e($r['from']); ?>&to=<?php echo e($r['to']); ?>&carrier=<?php echo urlencode($r['carrier']); ?>&date=<?php echo e($r['date']); ?>&cabin=<?php echo e($r['cabin']); ?>&price=<?php echo e($r['price']); ?>" class="btn btn-gold">Book</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
