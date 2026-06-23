<?php
$content = 'views/prospects/content_index.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_index.php';
}
require_once __DIR__ . '/../layout.php';
?>