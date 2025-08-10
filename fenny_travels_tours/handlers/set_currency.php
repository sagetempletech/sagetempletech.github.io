<?php
require_once __DIR__ . '/../services/helpers.php';
$code = strtoupper(trim($_REQUEST['code'] ?? ''));
$supported = get_option('currency.supported', []);
if ($code && in_array($code, $supported, true)) {
    $_SESSION['currency'] = $code;
}
$back = $_SERVER['HTTP_REFERER'] ?? '/';
redirect($back);