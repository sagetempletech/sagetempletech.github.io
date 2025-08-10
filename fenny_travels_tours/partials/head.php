<?php
$opts = read_options();
$biz = $opts['business'];
$theme = $opts['theme'];
$media = $opts['media'] ?? [];
$headingFont = $theme['heading_font'] ?? 'Poppins';
$bodyFont = $theme['body_font'] ?? 'Open Sans';
$gfHeading = str_replace(' ', '+', $headingFont);
$gfBody = str_replace(' ', '+', $bodyFont);
$mode = $theme['mode'] ?? 'light';
$heroBg = $media['hero'] ?? '/assets/images/hero.svg';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo e($biz['name']); ?></title>
  <meta name="description" content="Seamless and affordable travel experiences: flights, hotels, visas, insurance." />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=<?php echo $gfHeading; ?>:wght@400;600;700;800&family=<?php echo $gfBody; ?>:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>" />
  <link rel="icon" href="<?php echo e($media['favicon'] ?? '/assets/images/favicon.svg'); ?>" type="image/svg+xml" />
  <style>
    :root {
      --primary: <?php echo $theme['primary']; ?>;
      --secondary: <?php echo $theme['secondary']; ?>;
      --accent: <?php echo $theme['accent']; ?>;
      --bg-light: <?php echo $theme['bg_light']; ?>;
      --heading-font: '<?php echo e($headingFont); ?>';
      --body-font: '<?php echo e($bodyFont); ?>';
    }
    .hero .hero-bg { background-image: url('<?php echo e($heroBg); ?>'); }
  </style>
</head>
<body class="bg-gradient <?php echo $mode === 'dark' ? 'theme-dark' : ''; ?>">
