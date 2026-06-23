<?php
$content = 'views/specialites/content_show.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_show.php';
}
require_once __DIR__ . '/../layout.php';
?>