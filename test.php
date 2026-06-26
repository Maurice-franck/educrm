<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>1. PHP fonctionne</h2>";

// Test session
session_start();
echo "<h2>2. Sessions OK</h2>";

// Test base de données
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "<h2>3. ✅ Connexion DB OK</h2>";
    
    // Lister les utilisateurs
    $stmt = $db->query("SELECT id, email, role, statut FROM utilisateurs");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>4. Utilisateurs en base :</h2><table border='1' cellpadding='6'>";
    echo "<tr><th>ID</th><th>Email</th><th>Rôle</th><th>Statut</th></tr>";
    foreach ($users as $u) {
        echo "<tr><td>{$u['id']}</td><td>{$u['email']}</td><td><strong>{$u['role']}</strong></td><td>{$u['statut']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<h2>3. ❌ Connexion DB ÉCHOUÉE</h2>";
}

// Test mod_rewrite
echo "<h2>5. REQUEST_URI : " . $_SERVER['REQUEST_URI'] . "</h2>";
echo "<h2>6. PHP version : " . phpversion() . "</h2>";
?>
