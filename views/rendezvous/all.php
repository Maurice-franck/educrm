<?php
$content = 'views/rendezvous/content_all.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_all.php';
}
require_once __DIR__ . '/../layout.php';
?>