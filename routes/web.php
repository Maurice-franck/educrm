<?php
session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UtilisateurController.php';
require_once __DIR__ . '/../controllers/ParametreController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$base_path   = '/educrm';

$path = str_replace($base_path, '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);

switch (true) {

    // ── AUTH ────────────────────────────────────────────
    case $path === '/login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->processLogin();
        } else {
            $controller->showLogin();
        }
        break;

    case $path === '/logout':
    case $path === '/deconnexion':
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: /educrm/login');
        exit;

    // ── UTILISATEURS ────────────────────────────────────
    case $path === '/utilisateurs':
        $controller = new UtilisateurController();
        $controller->index();
        break;

    case $path === '/utilisateurs/store':
        $controller = new UtilisateurController();
        $controller->store();
        break;

    case (bool) preg_match('/^\/utilisateurs\/(\d+)\/edit$/', $path, $matches):
        $controller = new UtilisateurController();
        $controller->edit($matches[1]);
        break;

    case (bool) preg_match('/^\/utilisateurs\/(\d+)\/update$/', $path, $matches):
        $controller = new UtilisateurController();
        $controller->update($matches[1]);
        break;

    case (bool) preg_match('/^\/utilisateurs\/(\d+)\/deactivate$/', $path, $matches):
        $controller = new UtilisateurController();
        $controller->deactivate($matches[1]);
        break;

    case (bool) preg_match('/^\/utilisateurs\/(\d+)\/activate$/', $path, $matches):
        $controller = new UtilisateurController();
        $controller->activate($matches[1]);
        break;

    case (bool) preg_match('/^\/utilisateurs\/(\d+)\/reset-password$/', $path, $matches):
        $controller = new UtilisateurController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->resetPassword($matches[1]);
        } else {
            $controller->showResetPassword($matches[1]);
        }
        break;

    case (bool) preg_match('/^\/utilisateurs\/(\d+)$/', $path, $matches):
        $controller = new UtilisateurController();
        $controller->show($matches[1]);
        break;

    // ── PARAMÈTRES ──────────────────────────────────────
    case $path === '/parametres':
        $controller = new ParametreController();
        $controller->index();
        break;

    case $path === '/parametres/profil' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new ParametreController();
        $controller->updateProfil();
        break;

    case $path === '/parametres/mot-de-passe' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new ParametreController();
        $controller->updateMotDePasse();
        break;

    case $path === '/parametres/application' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new ParametreController();
        $controller->updateApplication();
        break;

    // ── 404 ─────────────────────────────────────────────
    default:
        http_response_code(404);
        echo "Page non trouvée : " . htmlspecialchars($path);
        break;
}
?>