<?php
// Ce fichier prépare le contenu et inclut le layout
$content = __DIR__ . '/content_index.php';

// Vérifier si le fichier content existe
if (!file_exists($content)) {
    die("Le fichier content_index.php est introuvable dans " . __DIR__);
}

// Inclure le layout principal
require_once __DIR__ . '/../layout.php';
?>