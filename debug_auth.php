<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Intercepter AVANT que index.php ne fasse quoi que ce soit
session_start();

echo "<h2>SESSION au chargement :</h2><pre>" . print_r($_SESSION, true) . "</pre>";
echo "<h2>SESSION ID : " . session_id() . "</h2>";
echo "<h2>REQUEST_URI : " . $_SERVER['REQUEST_URI'] . "</h2>";
echo "<h2>METHOD : " . $_SERVER['REQUEST_METHOD'] . "</h2>";

// Charger la config DB et le modèle Auth
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Auth.php';

// Tester le login avec des identifiants réels
$email = 'franck@educrm.com';   // ← change si besoin
$password = '123456';         // ← mets le vrai mot de passe ici

$database = new Database();
$db = $database->getConnection();
$authModel = new Auth($db);

echo "<h2>Test login avec $email :</h2>";
$result = $authModel->login($email, $password);

if ($result) {
    echo "<p style='color:green'>✅ login() retourne TRUE</p>";
    echo "<p>Rôle : " . $authModel->role . "</p>";
    echo "<p>Redirect vers : " . Auth::getRedirectByRole($authModel->role) . "</p>";
    
    // Simuler startSession
    $authModel->startSession();
    echo "<h2>Session après startSession() :</h2><pre>" . print_r($_SESSION, true) . "</pre>";
    echo "<p>isLoggedIn() : " . (Auth::isLoggedIn() ? '✅ TRUE' : '❌ FALSE') . "</p>";
} else {
    echo "<p style='color:red'>❌ login() retourne FALSE — mauvais mot de passe ou compte inactif</p>";
    
    // Vérifier le hash en base
    $stmt = $db->prepare("SELECT mot_de_passe, statut FROM utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "<p>Statut en base : " . $row['statut'] . "</p>";
        echo "<p>Hash en base : " . substr($row['mot_de_passe'], 0, 30) . "...</p>";
        echo "<p>password_verify('password', hash) : " . (password_verify($password, $row['mot_de_passe']) ? '✅ OUI' : '❌ NON') . "</p>";
        echo "<p>Comparaison directe ('password' === hash) : " . ($password === $row['mot_de_passe'] ? '✅ OUI' : '❌ NON') . "</p>";
    } else {
        echo "<p>❌ Email non trouvé en base</p>";
    }
}
