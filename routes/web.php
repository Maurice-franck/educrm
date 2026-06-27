<?php
// Démarrer la session
session_start();

// Routes pour la gestion des utilisateurs
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/educrm';

// Enlever le chemin de base
$path = str_replace($base_path, '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);

// Routing simple
switch($path) {
    case '/login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->processLogin();
        } else {
            $controller->showLogin();
        }
        break;

    case '/logout':
    case '/deconnexion':
        // Déconnexion directe sans instancier le contrôleur (pas besoin de DB)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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

    case '/utilisateurs':
        $controller = new UtilisateurController();
        $controller->index();
        break;
        
    case 'views/utilisateurs/create':
        $controller = new UtilisateurController();
        $controller->create();
        break;
        
    case '/utilisateurs/store':
        $controller = new UtilisateurController();
        $controller->store();
        break;
        
    case preg_match('/\/utilisateurs\/(\d+)\/edit/', $path, $matches) ? true : false:
        $controller = new UtilisateurController();
        $controller->edit($matches[1]);
        break;
        
    case preg_match('/\/utilisateurs\/(\d+)\/update/', $path, $matches) ? true : false:
        $controller = new UtilisateurController();
        $controller->update($matches[1]);
        break;
        
    case preg_match('/\/utilisateurs\/(\d+)/', $path, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET':
        $controller = new UtilisateurController();
        $controller->show($matches[1]);
        break;
        
    case preg_match('/\/utilisateurs\/(\d+)\/deactivate/', $path, $matches) ? true : false:
        $controller = new UtilisateurController();
        $controller->deactivate($matches[1]);
        break;
        
    case preg_match('/\/utilisateurs\/(\d+)\/activate/', $path, $matches) ? true : false:
        $controller = new UtilisateurController();
        $controller->activate($matches[1]);
        break;
        
    case preg_match('/\/utilisateurs\/(\d+)\/reset-password/', $path, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET':
        $controller = new UtilisateurController();
        $controller->showResetPassword($matches[1]);
        break;
        
    case preg_match('/\/utilisateurs\/(\d+)\/reset-password/', $path, $matches) && $_SERVER['REQUEST_METHOD'] == 'POST':
        $controller = new UtilisateurController();
        $controller->resetPassword($matches[1]);
        break;
        
    default:
        // Page 404
        http_response_code(404);
        echo "Page non trouvée";
        break;
}
?>