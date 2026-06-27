<?php
// controllers/ParametreController.php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/AuthMiddleware.php';
require_once __DIR__ . '/../config/database.php';

class ParametreController {

    private $utilisateurModel;
    private $settingModel;

    public function __construct() {
        AuthMiddleware::check();
        // Seul l'admin accède aux paramètres
       if ($_SESSION['user_role'] !== 'ADMIN') {
            header('Location: /educrm/dashboard');
            exit;
        }
       $database = new Database();
$this->utilisateurModel = new Utilisateur($database->getConnection());
        $this->settingModel     = new Setting();

    }

    // Page principale des paramètres (profil + app)
    public function index() {
        $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);
        $settings    = $this->settingModel->getAll();
        $content     = __DIR__ . '/../views/parametres/content_index.php';
        require_once __DIR__ . '/../views/parametres/index.php';
    }

    // Mise à jour du profil admin
    public function updateProfil() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /educrm/parametres');
            exit;
        }

       $id = $_SESSION['user_id'];
        $data = [
            'nom'       => trim($_POST['nom']       ?? ''),
            'prenom'    => trim($_POST['prenom']     ?? ''),
            'telephone' => trim($_POST['telephone']  ?? ''),
            'email'     => trim($_POST['email']      ?? ''),
        ];

        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email'])) {
            $_SESSION['error'] = "Nom, prénom et email sont obligatoires.";
            header('Location: /educrm/parametres');
            exit;
        }

        $existant = $this->utilisateurModel->getByEmail($data['email']);
        if ($existant && $existant['id'] != $id) {
            $_SESSION['error'] = "Cet email est déjà utilisé par un autre compte.";
            header('Location: /educrm/parametres');
            exit;
        }

        $this->utilisateurModel->updateProfil($id, $data);

        // Sync session
      $_SESSION['user_nom']       = $data['nom'];
$_SESSION['user_prenom']    = $data['prenom'];
$_SESSION['user_email']     = $data['email'];
$_SESSION['user_telephone'] = $data['telephone'];
        $_SESSION['success'] = "Profil mis à jour avec succès.";
        header('Location: /educrm/parametres#tab-profil');
        exit;
    }

    // Changement de mot de passe admin
    public function updateMotDePasse() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /educrm/parametres');
            exit;
        }

       $id = $_SESSION['user_id'];
        $utilisateur = $this->utilisateurModel->getById($id);
        $ancien      = $_POST['ancien_mot_de_passe']    ?? '';
        $nouveau     = $_POST['nouveau_mot_de_passe']   ?? '';
        $confirmer   = $_POST['confirmer_mot_de_passe'] ?? '';

        // Compatibilité mdp hashé ET mdp en clair (comptes de test)
        $ok = password_verify($ancien, $utilisateur['mot_de_passe'])
           || ($ancien === $utilisateur['mot_de_passe']);

        if (!$ok) {
            $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
            header('Location: /educrm/parametres#tab-mdp');
            exit;
        }
        if (strlen($nouveau) < 6) {
            $_SESSION['error'] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
            header('Location: /educrm/parametres#tab-mdp');
            exit;
        }
        if ($nouveau !== $confirmer) {
            $_SESSION['error'] = "Les deux mots de passe ne correspondent pas.";
            header('Location: /educrm/parametres#tab-mdp');
            exit;
        }

        $this->utilisateurModel->updateMotDePasse($id, password_hash($nouveau, PASSWORD_DEFAULT));
        $_SESSION['success'] = "Mot de passe modifié avec succès.";
        header('Location: /educrm/parametres#tab-mdp');
        exit;
    }

    // Mise à jour des paramètres de l'application (langue, fuseau, etc.)
    public function updateApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /educrm/parametres');
            exit;
        }

        $languesAutorisees = ['fr', 'en'];
        $fuseauxAutorises  = DateTimeZone::listIdentifiers();

       $_SESSION['app_langue'] = $langue;
        $fuseau = $_POST['app_fuseau'] ?? 'Africa/Douala';

        if (!in_array($langue, $languesAutorisees)) {
            $_SESSION['error'] = "Langue non valide.";
            header('Location: /educrm/parametres#tab-app');
            exit;
        }
        if (!in_array($fuseau, $fuseauxAutorises)) {
            $_SESSION['error'] = "Fuseau horaire non valide.";
            header('Location: /educrm/parametres#tab-app');
            exit;
        }

        $this->settingModel->update([
            'app_langue'    => $langue,
            'app_fuseau'    => $fuseau,
            'app_nom'       => trim($_POST['app_nom']       ?? 'EduCRM'),
            'app_slogan'    => trim($_POST['app_slogan']     ?? ''),
            'app_email'     => trim($_POST['app_email']      ?? ''),
            'app_telephone' => trim($_POST['app_telephone']  ?? ''),
        ]);

        // Appliquer immédiatement le fuseau horaire en session
        $_SESSION['app_fuseau'] = $fuseau;
        $_SESSION['app_langue'] = $langue;

        $_SESSION['success'] = "Paramètres de l'application enregistrés.";
        header('Location: /educrm/parametres#tab-app');
        exit;
    }
}
