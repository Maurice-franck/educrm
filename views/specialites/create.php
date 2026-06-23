<?php
$content = 'views/specialites/content_create.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_create.php';
}
require_once __DIR__ . '/../layout.php';
?>