<?php
require_once 'models/Utilisateur.php';

class UtilisateurController {
    private $db;
    private $utilisateur;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->utilisateur = new Utilisateur($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher la liste des utilisateurs
    public function index() {
        $stmt = $this->utilisateur->readAll();
        $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/utilisateurs/index.php';
    }
    
    // Afficher le formulaire d'ajout
    public function create() {
        require_once 'views/utilisateurs/create.php';
    }
    
    // Enregistrer un nouvel utilisateur
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->utilisateur->nom = $_POST['nom'];
            $this->utilisateur->prenom = $_POST['prenom'];
            $this->utilisateur->telephone = $_POST['telephone'];
            $this->utilisateur->email = $_POST['email'];
            $this->utilisateur->mot_de_passe = $_POST['mot_de_passe'];
            $this->utilisateur->role = $_POST['role'];
            
            // Vérifier si l'email existe déjà
            if($this->utilisateur->emailExists()) {
                $_SESSION['error'] = "Cet email est déjà utilisé par un autre utilisateur.";
                header("Location: /educrm/utilisateurs/create");
                exit();
            }
            
            if($this->utilisateur->create()) {
                $_SESSION['success'] = "Utilisateur ajouté avec succès.";
                header("Location: /educrm/utilisateurs");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de l'utilisateur.";
                header("Location: /educrm/utilisateurs/create");
            }
            exit();
        }
    }
    
    // Afficher le formulaire d'édition
    public function edit($id) {
        $this->utilisateur->id = $id;
        if($this->utilisateur->readOne()) {
            require_once 'views/utilisateurs/edit.php';
        } else {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: /educrm/utilisateurs");
            exit();
        }
    }
    
    // Mettre à jour un utilisateur
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->utilisateur->id = $id;
            $this->utilisateur->nom = $_POST['nom'];
            $this->utilisateur->prenom = $_POST['prenom'];
            $this->utilisateur->telephone = $_POST['telephone'];
            $this->utilisateur->email = $_POST['email'];
            $this->utilisateur->role = $_POST['role'];
            
            if($this->utilisateur->update()) {
                $_SESSION['success'] = "Utilisateur modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/utilisateurs");
            exit();
        }
    }
    
    // Afficher les détails d'un utilisateur
    public function show($id) {
        $this->utilisateur->id = $id;
        if($this->utilisateur->readOne()) {
            require_once 'views/utilisateurs/show.php';
        } else {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: /educrm/utilisateurs");
            exit();
        }
    }
    
    // Désactiver un utilisateur
    public function deactivate($id) {
        $this->utilisateur->id = $id;
        if($this->utilisateur->deactivate()) {
            $_SESSION['success'] = "Utilisateur désactivé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la désactivation.";
        }
        header("Location: /educrm/utilisateurs");
        exit();
    }
    
    // Réactiver un utilisateur
    public function activate($id) {
        $this->utilisateur->id = $id;
        if($this->utilisateur->activate()) {
            $_SESSION['success'] = "Utilisateur réactivé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la réactivation.";
        }
        header("Location: /educrm/utilisateurs");
        exit();
    }
    
    // Afficher le formulaire de réinitialisation de mot de passe
    public function showResetPassword($id) {
        $this->utilisateur->id = $id;
        if($this->utilisateur->readOne()) {
            require_once 'views/utilisateurs/reset_password.php';
        } else {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: /educrm/utilisateurs");
            exit();
        }
    }
    
    // Réinitialiser le mot de passe
    public function resetPassword($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if($new_password !== $confirm_password) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                header("Location: /educrm/utilisateurs/reset-password/$id");
                exit();
            }
            
            if(strlen($new_password) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
                header("Location: /educrm/utilisateurs/reset-password/$id");
                exit();
            }
            
            $this->utilisateur->id = $id;
            if($this->utilisateur->resetPassword($new_password)) {
                $_SESSION['success'] = "Mot de passe réinitialisé avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la réinitialisation.";
            }
            header("Location: /educrm/utilisateurs");
            exit();
        }
    }
}
?>