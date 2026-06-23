<?php
/**
 * Middleware d'authentification
 * À inclure en tête de chaque contrôleur protégé
 * 
 * Usage :
 *   AuthMiddleware::check();                              // tout rôle connecté
 *   AuthMiddleware::check(['ADMIN']);                     // admin uniquement
 *   AuthMiddleware::check(['ADMIN','CHEF_DEPARTEMENT']);  // multi-rôles
 */
require_once __DIR__ . '/../models/Auth.php';

class AuthMiddleware {

    /**
     * Vérifie la session et les rôles autorisés.
     * Redirige si non connecté ou rôle insuffisant.
     */
    public static function check(array $allowedRoles = []): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Non connecté → page de connexion
        if (!Auth::isLoggedIn()) {
            header('Location: /educrm/login');
            exit;
        }

        // Vérification du rôle si des rôles sont spécifiés
        if (!empty($allowedRoles) && !in_array($_SESSION['user_role'], $allowedRoles)) {
            http_response_code(403);
            self::renderAccessDenied();
            exit;
        }
    }

    /**
     * Page d'accès refusé intégrée
     */
    private static function renderAccessDenied(): void {
        $role = $_SESSION['user_role'] ?? 'Inconnu';
        $nom  = ($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? '');
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Accès refusé — EduCRM</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
            <style>
                body { font-family:'Inter',sans-serif; background:#0A1628; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
                .box { background:#fff; border-radius:20px; padding:3rem; text-align:center; max-width:420px; width:90%; }
                .icon { font-size:3.5rem; color:#E74C3C; margin-bottom:1rem; }
                h1 { color:#0A1628; font-size:1.5rem; margin-bottom:.5rem; }
                p { color:#8896A7; font-size:.9rem; }
                .badge { display:inline-block; margin:1rem 0; padding:.35rem .85rem; background:#EBF5FB; color:#1E3A5F; border-radius:20px; font-size:.8rem; font-weight:600; }
                a { display:inline-block; margin-top:1.5rem; padding:.75rem 2rem; background:#1E3A5F; color:#fff; border-radius:10px; text-decoration:none; font-weight:600; font-size:.9rem; }
                a:hover { background:#0A1628; }
            </style>
        </head>
        <body>
            <div class="box">
                <div class="icon"><i class="fas fa-ban"></i></div>
                <h1>Accès refusé</h1>
                <p>Votre rôle <span class="badge">$role</span> ne vous autorise pas à accéder à cette page.</p>
                <p style="margin-top:.5rem">Connecté en tant que <strong>$nom</strong></p>
                <a href="/educrm/dashboard"><i class="fas fa-arrow-left" style="margin-right:6px"></i>Retour à l'accueil</a>
            </div>
        </body>
        </html>
        HTML;
    }
}
?>
