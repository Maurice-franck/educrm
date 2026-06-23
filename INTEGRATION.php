<?php
/**
 * ════════════════════════════════════════════════════
 *  ROUTES À AJOUTER DANS index.php (EduCRM)
 *  Insérez ce bloc AVANT le switch($path) existant
 * ════════════════════════════════════════════════════
 *
 * 1. Démarrer la session tout en haut du fichier
 * 2. Inclure le contrôleur Auth
 * 3. Ajouter les routes login/logout dans le switch
 */

// ─── ÉTAPE 1 : En tête du fichier (remplacer session_start() existant) ───────
session_start();

// ─── ÉTAPE 2 : Inclure les nouveaux fichiers ──────────────────────────────────
require_once 'controllers/AuthController.php';
require_once 'controllers/AuthMiddleware.php';

// ─── ÉTAPE 3 : Routes à ajouter dans le switch($path) ────────────────────────
/*
    // Afficher la page de connexion
    case '/login':
        $controller = new AuthController();
        $controller->showLogin();
        break;

    // Traiter le formulaire POST
    case '/login/process':
        $controller = new AuthController();
        $controller->processLogin();
        break;

    // Déconnexion
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // Rediriger '/' vers login si non connecté
    case '/':
        if (Auth::isLoggedIn()) {
            $redirect = Auth::getRedirectByRole($_SESSION['user_role']);
            header('Location: ' . $redirect);
        } else {
            header('Location: /educrm/login');
        }
        exit;
        break;
*/

// ─── ÉTAPE 4 : Protéger vos contrôleurs existants ────────────────────────────
// Ajoutez cette ligne en tête de chaque méthode de contrôleur selon le rôle :
/*
    // Dans DashboardController::index()
    AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);

    // Dans UtilisateurController (toutes les méthodes)
    AuthMiddleware::check(['ADMIN']);

    // Dans ProspectController
    AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);

    // Dans RelanceController
    AuthMiddleware::check(['ADMIN', 'MARKETISTE']);

    // Dans RendezVousController
    AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
*/

// ─── ÉTAPE 5 : Mettre à jour le form action dans login.php ───────────────────
// L'action du formulaire pointe vers /educrm/login/process (méthode POST)
// Le switch redirige les POST vers processLogin() et les GET vers showLogin()
// Vous pouvez distinguer GET/POST ainsi dans la route /login :
/*
    case '/login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->processLogin();
        } else {
            $controller->showLogin();
        }
        break;
*/
?>
