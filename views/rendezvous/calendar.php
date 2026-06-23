<?php
$content = 'views/rendezvous/content_calendar.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_calendar.php';
}
require_once __DIR__ . '/../layout.php';
?>