<?php
/**
 * EduCRM — Point d'entrée principal
 * Architecture MVC — Programmation Orientée Objet
 * Routes : login, logout + toutes les routes métier
 */

// ─── SESSION ──────────────────────────────────────────────────────────────────
session_start();

// ─── CONTRÔLEURS ──────────────────────────────────────────────────────────────
require_once 'controllers/AuthController.php';
require_once 'controllers/AuthMiddleware.php';
require_once 'controllers/UtilisateurController.php';
require_once 'controllers/DepartementController.php';
require_once 'controllers/SpecialiteController.php';
require_once 'controllers/SourceMarketingController.php';
require_once 'controllers/ProspectController.php';
require_once 'controllers/RelanceController.php';
require_once 'controllers/RendezVousController.php';
require_once 'controllers/DashboardController.php';

// ─── ROUTING ──────────────────────────────────────────────────────────────────
$request_uri = $_SERVER['REQUEST_URI'];
$base_path   = '/educrm';

$path = str_replace($base_path, '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);

switch ($path) {

    // ══════════════════════════════════════════════
    //  AUTHENTIFICATION (publiques — pas de session requise)
    // ══════════════════════════════════════════════

    case '/login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->processLogin();
        } else {
            $controller->showLogin();
        }
        break;

    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // ══════════════════════════════════════════════
    //  ACCUEIL : redirection selon rôle
    // ══════════════════════════════════════════════

    case '/':
        if (Auth::isLoggedIn()) {
            $redirect = Auth::getRedirectByRole($_SESSION['user_role']);
            header('Location: ' . $redirect);
        } else {
            header('Location: /educrm/login');
        }
        exit;

    // ══════════════════════════════════════════════
    //  DASHBOARD (Admin + Chef de département)
    // ══════════════════════════════════════════════

    case '/dashboard':
        AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
        $controller = new DashboardController();
        $controller->index();
        break;

    // ══════════════════════════════════════════════
    //  UTILISATEURS (Admin uniquement)
    // ══════════════════════════════════════════════

    case '/utilisateurs':
        AuthMiddleware::check(['ADMIN']);
        $controller = new UtilisateurController();
        $controller->index();
        break;

    case '/utilisateurs/create':
        AuthMiddleware::check(['ADMIN']);
        $controller = new UtilisateurController();
        $controller->create();
        break;

    case '/utilisateurs/store':
        AuthMiddleware::check(['ADMIN']);
        $controller = new UtilisateurController();
        $controller->store();
        break;

    // ══════════════════════════════════════════════
    //  DÉPARTEMENTS (Admin)
    // ══════════════════════════════════════════════

    case '/departements':
        AuthMiddleware::check(['ADMIN']);
        $controller = new DepartementController();
        $controller->index();
        break;

    case '/departements/create':
        AuthMiddleware::check(['ADMIN']);
        $controller = new DepartementController();
        $controller->create();
        break;

    case '/departements/store':
        AuthMiddleware::check(['ADMIN']);
        $controller = new DepartementController();
        $controller->store();
        break;

    // ══════════════════════════════════════════════
    //  SPÉCIALITÉS (Admin + Chef de département)
    // ══════════════════════════════════════════════

    case '/specialites':
        AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
        $controller = new SpecialiteController();
        $controller->index();
        break;

    case '/specialites/create':
        AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
        $controller = new SpecialiteController();
        $controller->create();
        break;

    case '/specialites/store':
        AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
        $controller = new SpecialiteController();
        $controller->store();
        break;

    // ══════════════════════════════════════════════
    //  SOURCES MARKETING (Admin)
    // ══════════════════════════════════════════════

    case '/sources':
        AuthMiddleware::check(['ADMIN']);
        $controller = new SourceMarketingController();
        $controller->index();
        break;

    case '/sources/create':
        AuthMiddleware::check(['ADMIN']);
        $controller = new SourceMarketingController();
        $controller->create();
        break;

    case '/sources/store':
        AuthMiddleware::check(['ADMIN']);
        $controller = new SourceMarketingController();
        $controller->store();
        break;

    // ══════════════════════════════════════════════
    //  PROSPECTS (Admin + Marketiste + Chef)
    // ══════════════════════════════════════════════

    case '/prospects':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
        $controller = new ProspectController();
        $controller->index();
        break;

    case '/prospects/create':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
        $controller = new ProspectController();
        $controller->create();
        break;

    case '/prospects/store':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
        $controller = new ProspectController();
        $controller->store();
        break;

    case '/prospects/export':
        AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
        $controller = new ProspectController();
        $controller->export();
        break;

    // ══════════════════════════════════════════════
    //  RELANCES (Admin + Marketiste)
    // ══════════════════════════════════════════════

    case '/relances':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
        $controller = new RelanceController();
        $controller->index();
        break;

    case '/relances/all':
        AuthMiddleware::check(['ADMIN']);
        $controller = new RelanceController();
        $controller->all();
        break;

    case '/relances/create':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
        $controller = new RelanceController();
        $controller->create();
        break;

    case '/relances/store':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
        $controller = new RelanceController();
        $controller->store();
        break;

    case '/relances/report':
        AuthMiddleware::check(['ADMIN']);
        $controller = new RelanceController();
        $controller->report();
        break;

    case '/relances/export':
        AuthMiddleware::check(['ADMIN']);
        $controller = new RelanceController();
        $controller->export();
        break;

    // ══════════════════════════════════════════════
    //  RENDEZ-VOUS (Tous)
    // ══════════════════════════════════════════════

    case '/rendezvous':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
        $controller = new RendezVousController();
        $controller->index();
        break;

    case '/rendezvous/all':
        AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
        $controller = new RendezVousController();
        $controller->all();
        break;

    case '/rendezvous/create':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
        $controller = new RendezVousController();
        $controller->create();
        break;

    case '/rendezvous/store':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
        $controller = new RendezVousController();
        $controller->store();
        break;

    case '/rendezvous/calendar':
        AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
        $controller = new RendezVousController();
        $controller->calendar();
        break;

    case '/rendezvous/export':
        AuthMiddleware::check(['ADMIN']);
        $controller = new RendezVousController();
        $controller->export();
        break;

    // ══════════════════════════════════════════════
    //  ROUTES DYNAMIQUES (avec paramètres ID)
    // ══════════════════════════════════════════════

    default:
        // --- Utilisateurs ---
        if (preg_match('/\/utilisateurs\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new UtilisateurController())->edit($m[1]);
        } elseif (preg_match('/\/utilisateurs\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new UtilisateurController())->update($m[1]);
        } elseif (preg_match('/\/utilisateurs\/(\d+)\/deactivate/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new UtilisateurController())->deactivate($m[1]);
        } elseif (preg_match('/\/utilisateurs\/(\d+)\/activate/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new UtilisateurController())->activate($m[1]);
        } elseif (preg_match('/\/utilisateurs\/(\d+)\/reset-password/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            $_SERVER['REQUEST_METHOD'] === 'GET'
                ? (new UtilisateurController())->showResetPassword($m[1])
                : (new UtilisateurController())->resetPassword($m[1]);
        } elseif (preg_match('/\/utilisateurs\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new UtilisateurController())->show($m[1]);
        }
        // --- Départements ---
        elseif (preg_match('/\/departements\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new DepartementController())->edit($m[1]);
        } elseif (preg_match('/\/departements\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new DepartementController())->update($m[1]);
        } elseif (preg_match('/\/departements\/(\d+)\/delete/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new DepartementController())->delete($m[1]);
        } elseif (preg_match('/\/departements\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new DepartementController())->show($m[1]);
        }
        // --- Spécialités ---
        elseif (preg_match('/\/specialites\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
            (new SpecialiteController())->edit($m[1]);
        } elseif (preg_match('/\/specialites\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
            (new SpecialiteController())->update($m[1]);
        } elseif (preg_match('/\/specialites\/(\d+)\/delete/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new SpecialiteController())->delete($m[1]);
        } elseif (preg_match('/\/specialites\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
            (new SpecialiteController())->show($m[1]);
        }
        // --- Sources marketing ---
        elseif (preg_match('/\/sources\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new SourceMarketingController())->edit($m[1]);
        } elseif (preg_match('/\/sources\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new SourceMarketingController())->update($m[1]);
        } elseif (preg_match('/\/sources\/(\d+)\/delete/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new SourceMarketingController())->delete($m[1]);
        }
        // --- Prospects ---
        elseif (preg_match('/\/prospects\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
            (new ProspectController())->edit($m[1]);
        } elseif (preg_match('/\/prospects\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
            (new ProspectController())->update($m[1]);
        } elseif (preg_match('/\/prospects\/(\d+)\/change-statut/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
            (new ProspectController())->changeStatut($m[1]);
        } elseif (preg_match('/\/prospects\/(\d+)\/reassign-marketiste/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new ProspectController())->reassignMarketiste($m[1]);
        } elseif (preg_match('/\/prospects\/(\d+)\/reassign-specialite/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'CHEF_DEPARTEMENT']);
            (new ProspectController())->reassignSpecialite($m[1]);
        } elseif (preg_match('/\/prospects\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
            (new ProspectController())->show($m[1]);
        }
        // --- Relances ---
        elseif (preg_match('/\/relances\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
            (new RelanceController())->edit($m[1]);
        } elseif (preg_match('/\/relances\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
            (new RelanceController())->update($m[1]);
        } elseif (preg_match('/\/relances\/(\d+)\/delete/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new RelanceController())->delete($m[1]);
        } elseif (preg_match('/\/relances\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE']);
            (new RelanceController())->show($m[1]);
        }
        // --- Rendez-vous ---
        elseif (preg_match('/\/rendezvous\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
            (new RendezVousController())->edit($m[1]);
        } elseif (preg_match('/\/rendezvous\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
            (new RendezVousController())->update($m[1]);
        } elseif (preg_match('/\/rendezvous\/(\d+)\/delete/', $path, $m)) {
            AuthMiddleware::check(['ADMIN']);
            (new RendezVousController())->delete($m[1]);
        } elseif (preg_match('/\/rendezvous\/(\d+)\/change-statut/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
            (new RendezVousController())->changeStatut($m[1]);
        } elseif (preg_match('/\/rendezvous\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['ADMIN', 'MARKETISTE', 'CHEF_DEPARTEMENT']);
            (new RendezVousController())->show($m[1]);
        }
        // --- 404 ---
        else {
            http_response_code(404);
            echo "<!DOCTYPE html>
            <html><head><title>404</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head><body>
            <div class='container text-center mt-5'>
                <div class='alert alert-danger'>
                    <h1>404 — Page non trouvée</h1>
                    <a href='/educrm/' class='btn btn-primary mt-3'>Retour à l'accueil</a>
                </div>
            </div></body></html>";
        }
        break;
}
?>
