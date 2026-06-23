<?php
$content = 'views/departements/content_edit.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_edit.php';
}
require_once __DIR__ . '/../layout.php';
?>