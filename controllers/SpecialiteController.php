<?php
require_once 'models/Specialite.php';
require_once 'models/Departement.php';

class SpecialiteController {
    private $db;
    private $specialite;
    private $departement;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->specialite = new Specialite($this->db);
        $this->departement = new Departement($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher la liste des spécialités
    public function index() {
        $departement_id = isset($_GET['departement_id']) ? $_GET['departement_id'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if($departement_id) {
            $stmt = $this->specialite->readByDepartement($departement_id);
            $current_departement = $this->getDepartementName($departement_id);
        } elseif(!empty($search)) {
            $stmt = $this->specialite->search($search);
            $current_departement = null;
        } else {
            $stmt = $this->specialite->readAll();
            $current_departement = null;
        }
        
        $specialites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer tous les départements pour le filtre
        $deptStmt = $this->departement->readAll();
        $departements = $deptStmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/specialites/index.php';
    }
    
    private function getDepartementName($id) {
        $this->departement->id = $id;
        if($this->departement->readOne()) {
            return $this->departement->nom;
        }
        return null;
    }
    
    // Afficher le formulaire d'ajout
    public function create() {
        $stmt = $this->departement->readAll();
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(empty($departements)) {
            $_SESSION['error'] = "Aucun département trouvé. Veuillez d'abord créer un département.";
            header("Location: /educrm/departements/create");
            exit();
        }
        
        require_once 'views/specialites/create.php';
    }
    
    // Enregistrer une nouvelle spécialité
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->specialite->departement_id = $_POST['departement_id'];
            $this->specialite->nom = $_POST['nom'];
            $this->specialite->description = $_POST['description'];
            
            if($this->specialite->nameExistsInDepartement()) {
                $_SESSION['error'] = "Cette spécialité existe déjà dans ce département.";
                header("Location: /educrm/specialites/create");
                exit();
            }
            
            if($this->specialite->create()) {
                $_SESSION['success'] = "Spécialité ajoutée avec succès.";
                header("Location: /educrm/specialites");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de la spécialité.";
                header("Location: /educrm/specialites/create");
            }
            exit();
        }
    }
    
    // Afficher les détails d'une spécialité
    public function show($id) {
        $this->specialite->id = $id;
        if($this->specialite->readOne()) {
            require_once 'views/specialites/show.php';
        } else {
            $_SESSION['error'] = "Spécialité non trouvée.";
            header("Location: /educrm/specialites");
            exit();
        }
    }
    
    // Afficher le formulaire d'édition - CORRIGÉ
    public function edit($id) {
        $this->specialite->id = $id;
        if($this->specialite->readOne()) {
            // Récupérer tous les départements pour le select
            $stmt = $this->departement->readAll();
            $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            require_once 'views/specialites/edit.php';
        } else {
            $_SESSION['error'] = "Spécialité non trouvée.";
            header("Location: /educrm/specialites");
            exit();
        }
    }
    
    // Mettre à jour une spécialité
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->specialite->id = $id;
            $this->specialite->departement_id = $_POST['departement_id'];
            $this->specialite->nom = $_POST['nom'];
            $this->specialite->description = $_POST['description'];
            
            if($this->specialite->nameExistsInDepartement()) {
                $_SESSION['error'] = "Cette spécialité existe déjà dans ce département.";
                header("Location: /educrm/specialites/" . $id . "/edit");
                exit();
            }
            
            if($this->specialite->update()) {
                $_SESSION['success'] = "Spécialité modifiée avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/specialites");
            exit();
        }
    }
    
    // Supprimer une spécialité
    public function delete($id) {
        $this->specialite->id = $id;
        
        if($this->specialite->delete()) {
            $_SESSION['success'] = "Spécialité supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header("Location: /educrm/specialites");
        exit();
    }
}
?>