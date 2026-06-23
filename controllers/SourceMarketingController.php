<?php
require_once 'models/SourceMarketing.php';

class SourceMarketingController {
    private $db;
    private $source;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->source = new SourceMarketing($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher la liste des sources
    public function index() {
        $stmt = $this->source->countProspectsBySource();
        $sources = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/sources/index.php';
    }
    
    // Afficher le formulaire d'ajout
    public function create() {
        require_once 'views/sources/create.php';
    }
    
    // Enregistrer une nouvelle source
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->source->nom = $_POST['nom'];
            
            // Vérifier si le nom existe déjà
            if($this->source->nameExists()) {
                $_SESSION['error'] = "Cette source marketing existe déjà.";
                header("Location: /educrm/sources/create");
                exit();
            }
            
            if($this->source->create()) {
                $_SESSION['success'] = "Source marketing ajoutée avec succès.";
                header("Location: /educrm/sources");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de la source.";
                header("Location: /educrm/sources/create");
            }
            exit();
        }
    }
    
    // Afficher le formulaire d'édition
    public function edit($id) {
        $this->source->id = $id;
        if($this->source->readOne()) {
            require_once 'views/sources/edit.php';
        } else {
            $_SESSION['error'] = "Source non trouvée.";
            header("Location: /educrm/sources");
            exit();
        }
    }
    
    // Mettre à jour une source
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->source->id = $id;
            $this->source->nom = $_POST['nom'];
            
            // Vérifier si le nom existe déjà
            if($this->source->nameExists()) {
                $_SESSION['error'] = "Cette source marketing existe déjà.";
                header("Location: /educrm/sources/" . $id . "/edit");
                exit();
            }
            
            if($this->source->update()) {
                $_SESSION['success'] = "Source marketing modifiée avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/sources");
            exit();
        }
    }
    
    // Supprimer une source
    public function delete($id) {
        $this->source->id = $id;
        
        if($this->source->delete()) {
            $_SESSION['success'] = "Source marketing supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Impossible de supprimer cette source car elle est utilisée par des prospects.";
        }
        header("Location: /educrm/sources");
        exit();
    }
}
?>