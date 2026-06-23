<?php 
$content = 'views/utilisateurs/content_reset_password.php';
if (!file_exists($content)) {
    $content = __DIR__ . '/content_reset_password.php';
}
require_once __DIR__ . '/../layout.php';
?>