<?php
$content = 'views/relances/content_report.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_report.php';
}
require_once __DIR__ . '/../layout.php';
?>