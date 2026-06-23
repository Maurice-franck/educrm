<?php
require_once 'models/Departement.php';

class DepartementController {
    private $db;
    private $departement;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->departement = new Departement($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher la liste des départements
    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if(!empty($search)) {
            $stmt = $this->departement->search($search);
        } else {
            $stmt = $this->departement->readAll();
        }
        
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/departements/index.php';
    }
    
    // Afficher le formulaire d'ajout
    public function create() {
        require_once 'views/departements/create.php';
    }
    
    // Enregistrer un nouveau département
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->departement->nom = $_POST['nom'];
            $this->departement->description = $_POST['description'];
            
            if($this->departement->create()) {
                $_SESSION['success'] = "Département ajouté avec succès.";
                header("Location: /educrm/departements");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du département.";
                header("Location: /educrm/departements/create");
            }
            exit();
        }
    }
    
    // Afficher les détails d'un département
    public function show($id) {
        $this->departement->id = $id;
        if($this->departement->readOne()) {
            require_once 'views/departements/show.php';
        } else {
            $_SESSION['error'] = "Département non trouvé.";
            header("Location: /educrm/departements");
            exit();
        }
    }
    
    // Afficher le formulaire d'édition
    public function edit($id) {
        $this->departement->id = $id;
        if($this->departement->readOne()) {
            require_once 'views/departements/edit.php';
        } else {
            $_SESSION['error'] = "Département non trouvé.";
            header("Location: /educrm/departements");
            exit();
        }
    }
    
    // Mettre à jour un département
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->departement->id = $id;
            $this->departement->nom = $_POST['nom'];
            $this->departement->description = $_POST['description'];
            
            if($this->departement->update()) {
                $_SESSION['success'] = "Département modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/departements");
            exit();
        }
    }
    
    // Supprimer un département
    public function delete($id) {
        $this->departement->id = $id;
        
        if($this->departement->delete()) {
            $_SESSION['success'] = "Département supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Impossible de supprimer ce département car il contient des spécialités.";
        }
        header("Location: /educrm/departements");
        exit();
    }
}
?>