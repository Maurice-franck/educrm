<?php
/**
 * Contrôleur AuthController - Gère les actions login / logout
 * Architecture MVC - Programmation Orientée Objet
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Auth.php';

class AuthController {

    private $db;
    private $authModel;

    public function __construct() {
        // La connexion DB n'est nécessaire que pour le login
        // Le logout n'en a pas besoin
        $database = new Database();
        $this->db = $database->getConnection();
        $this->authModel = new Auth($this->db);
    }

    /**
     * Affiche la page de connexion (GET /login)
     */
    public function showLogin(): void {
        // Si déjà connecté, rediriger vers le bon tableau de bord
        if (Auth::isLoggedIn()) {
            $redirect = Auth::getRedirectByRole($_SESSION['user_role']);
            header('Location: ' . $redirect);
            exit;
        }
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        require_once __DIR__ . '/../views/login.php';
    }

    /**
     * Traite le formulaire de connexion (POST /login)
     */
    public function processLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /educrm/login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation basique
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Veuillez remplir tous les champs.';
            header('Location: /educrm/login');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['login_error'] = 'Adresse email invalide.';
            header('Location: /educrm/login');
            exit;
        }

        // Tentative d'authentification
        if ($this->authModel->login($email, $password)) {
            $this->authModel->startSession();
            $redirect = Auth::getRedirectByRole($this->authModel->role);
            header('Location: ' . $redirect);
            exit;
        } else {
            $_SESSION['login_error'] = 'Email ou mot de passe incorrect, ou compte désactivé.';
            header('Location: /educrm/login');
            exit;
        }
    }

    /**
     * Déconnecte l'utilisateur (POST /logout)
     */
    public function logout(): void {
        // S'assurer que la session est démarrée avant de la détruire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vider les variables de session
        $_SESSION = [];

        // Supprimer le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Détruire la session
        session_destroy();

        // Rediriger vers login
        header('Location: /educrm/login');
        exit;
    }
}
?>