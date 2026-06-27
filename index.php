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
require_once 'controllers/marketiste/MarketisteSupervisionController.php';
require_once 'controllers/marketiste/MarketisteRendezVousController.php';
require_once 'controllers/marketiste/MarketisteProspectController.php';
require_once 'controllers/chef-departement/ChefDepartementSupervisionController.php';
require_once 'controllers/chef-departement/ChefDepartementRendezVousController.php';
require_once 'controllers/chef-departement/ChefDepartementProspectController.php';

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
            exit;
        } else {
            header('Location: /educrm/login');
            exit;
        }

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
    //  ESPACE MARKETISTE (Marketiste uniquement)
    //  Toutes les données sont filtrées sur $_SESSION['user_id']
    // ══════════════════════════════════════════════

    // --- Supervision (relances) ---
    case '/marketiste/supervision':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteSupervisionController();
        $controller->index();
        break;

    case '/marketiste/supervision/all':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteSupervisionController();
        $controller->all();
        break;

    case '/marketiste/supervision/create':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteSupervisionController();
        $controller->create();
        break;

    case '/marketiste/supervision/store':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteSupervisionController();
        $controller->store();
        break;

    // --- Rendez-vous ---
    case '/marketiste/rendezvous':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteRendezVousController();
        $controller->index();
        break;

    case '/marketiste/rendezvous/all':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteRendezVousController();
        $controller->all();
        break;

    case '/marketiste/rendezvous/calendar':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteRendezVousController();
        $controller->calendar();
        break;

    case '/marketiste/rendezvous/create':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteRendezVousController();
        $controller->create();
        break;

    case '/marketiste/rendezvous/store':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteRendezVousController();
        $controller->store();
        break;

    // --- Prospects ---
    case '/marketiste/prospects':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteProspectController();
        $controller->index();
        break;

    case '/marketiste/prospects/create':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteProspectController();
        $controller->create();
        break;

    case '/marketiste/prospects/store':
        AuthMiddleware::check(['MARKETISTE']);
        $controller = new MarketisteProspectController();
        $controller->store();
        break;

    // ══════════════════════════════════════════════
    //  ESPACE CHEF DE DÉPARTEMENT
    //  Visualisation élargie à tout le département ($_SESSION['departement_id'])
    // ══════════════════════════════════════════════

    // --- Supervision (relances) ---
    case '/chef-departement/supervision':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementSupervisionController();
        $controller->index();
        break;

    case '/chef-departement/supervision/all':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementSupervisionController();
        $controller->all();
        break;

    case '/chef-departement/supervision/create':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementSupervisionController();
        $controller->create();
        break;

    case '/chef-departement/supervision/store':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementSupervisionController();
        $controller->store();
        break;

    // --- Rendez-vous ---
    case '/chef-departement/rendezvous':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementRendezVousController();
        $controller->index();
        break;

    case '/chef-departement/rendezvous/all':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementRendezVousController();
        $controller->all();
        break;

    case '/chef-departement/rendezvous/calendar':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementRendezVousController();
        $controller->calendar();
        break;

    case '/chef-departement/rendezvous/create':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementRendezVousController();
        $controller->create();
        break;

    case '/chef-departement/rendezvous/store':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementRendezVousController();
        $controller->store();
        break;

    // --- Prospects ---
    case '/chef-departement/prospects':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementProspectController();
        $controller->index();
        break;

    case '/chef-departement/prospects/create':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementProspectController();
        $controller->create();
        break;

    case '/chef-departement/prospects/store':
        AuthMiddleware::check(['CHEF_DEPARTEMENT']);
        $controller = new ChefDepartementProspectController();
        $controller->store();
        break;

    // ══════════════════════════════════════════════
    //  ROUTES DYNAMIQUES (avec paramètres ID)
    // ══════════════════════════════════════════════

    default:
        // --- Espace Chef de Département : Supervision (relances) ---
        // IMPORTANT : testées en premier (même raison que pour /marketiste/...)
        if (preg_match('/\/chef-departement\/supervision\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementSupervisionController())->edit($m[1]);
        } elseif (preg_match('/\/chef-departement\/supervision\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementSupervisionController())->update($m[1]);
        } elseif (preg_match('/\/chef-departement\/supervision\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementSupervisionController())->show($m[1]);
        }
        // --- Espace Chef de Département : Rendez-vous ---
        elseif (preg_match('/\/chef-departement\/rendezvous\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementRendezVousController())->edit($m[1]);
        } elseif (preg_match('/\/chef-departement\/rendezvous\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementRendezVousController())->update($m[1]);
        } elseif (preg_match('/\/chef-departement\/rendezvous\/(\d+)\/change-statut/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementRendezVousController())->changeStatut($m[1]);
        } elseif (preg_match('/\/chef-departement\/rendezvous\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementRendezVousController())->show($m[1]);
        }
        // --- Espace Chef de Département : Prospects ---
        elseif (preg_match('/\/chef-departement\/prospects\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementProspectController())->edit($m[1]);
        } elseif (preg_match('/\/chef-departement\/prospects\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementProspectController())->update($m[1]);
        } elseif (preg_match('/\/chef-departement\/prospects\/(\d+)\/change-statut/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementProspectController())->changeStatut($m[1]);
        } elseif (preg_match('/\/chef-departement\/prospects\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['CHEF_DEPARTEMENT']);
            (new ChefDepartementProspectController())->show($m[1]);
        }
        // --- Espace Marketiste : Supervision (relances) ---
        elseif (preg_match('/\/marketiste\/supervision\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteSupervisionController())->edit($m[1]);
        } elseif (preg_match('/\/marketiste\/supervision\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteSupervisionController())->update($m[1]);
        } elseif (preg_match('/\/marketiste\/supervision\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteSupervisionController())->show($m[1]);
        }
        // --- Espace Marketiste : Rendez-vous ---
        elseif (preg_match('/\/marketiste\/rendezvous\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteRendezVousController())->edit($m[1]);
        } elseif (preg_match('/\/marketiste\/rendezvous\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteRendezVousController())->update($m[1]);
        } elseif (preg_match('/\/marketiste\/rendezvous\/(\d+)\/change-statut/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteRendezVousController())->changeStatut($m[1]);
        } elseif (preg_match('/\/marketiste\/rendezvous\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteRendezVousController())->show($m[1]);
        }
        // --- Espace Marketiste : Prospects ---
        elseif (preg_match('/\/marketiste\/prospects\/(\d+)\/edit/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteProspectController())->edit($m[1]);
        } elseif (preg_match('/\/marketiste\/prospects\/(\d+)\/update/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteProspectController())->update($m[1]);
        } elseif (preg_match('/\/marketiste\/prospects\/(\d+)\/change-statut/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteProspectController())->changeStatut($m[1]);
        } elseif (preg_match('/\/marketiste\/prospects\/(\d+)/', $path, $m)) {
            AuthMiddleware::check(['MARKETISTE']);
            (new MarketisteProspectController())->show($m[1]);
        }
        // --- Utilisateurs ---
        elseif (preg_match('/\/utilisateurs\/(\d+)\/edit/', $path, $m)) {
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
        // --- Prospects (admin / chef / marketiste sur routes non-marketiste) ---
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
        // --- Rendez-vous (admin / chef / marketiste sur routes non-marketiste) ---
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
