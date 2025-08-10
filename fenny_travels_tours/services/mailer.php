<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/helpers.php';

function send_mail(string $to, string $subject, string $html, string $from = null): bool {
    $from = $from ?: (get_option('notifications.email') ?: 'no-reply@localhost');

    // Very basic mail() usage; on many dev setups this may not send real mail.
    // We always log emails to storage/logs/mailer.log
    $headers = "MIME-Version: 1.0
";
    $headers .= "Content-type:text/html;charset=UTF-8
";
    $headers .= "From: $from
";

    $ok = @mail($to, $subject, $html, $headers);
    write_log('mailer.log', "To=$to | Subject=$subject | Sent=" . ($ok ? 'yes' : 'no') . "
---
$html
---
");
    return $ok;
}
