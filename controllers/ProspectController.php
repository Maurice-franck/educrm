<?php
require_once 'models/Prospect.php';
require_once 'models/Specialite.php';
require_once 'models/SourceMarketing.php';
require_once 'models/Departement.php';
require_once 'models/Utilisateur.php';

class ProspectController {
    private $db;
    private $prospect;
    private $specialite;
    private $source;
    private $departement;
    private $utilisateur;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->prospect = new Prospect($this->db);
        $this->specialite = new Specialite($this->db);
        $this->source = new SourceMarketing($this->db);
        $this->departement = new Departement($this->db);
        $this->utilisateur = new Utilisateur($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher la liste des prospects
    public function index() {
        // Récupérer les filtres
        $filters = [
            'departement_id' => isset($_GET['departement_id']) ? $_GET['departement_id'] : null,
            'specialite_id' => isset($_GET['specialite_id']) ? $_GET['specialite_id'] : null,
            'source_id' => isset($_GET['source_id']) ? $_GET['source_id'] : null,
            'statut' => isset($_GET['statut']) ? $_GET['statut'] : null,
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'search' => isset($_GET['search']) ? $_GET['search'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];
        
        $stmt = $this->prospect->readAll($filters);
        $prospects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer les données pour les filtres
        $departements = $this->departement->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $specialites = $this->specialite->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $stats = $this->prospect->getStats();
        
        require_once 'views/prospects/index.php';
    }
    
    // Afficher le formulaire d'ajout
    public function create() {
        $specialites = $this->specialite->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/prospects/create.php';
    }
    
    // Enregistrer un nouveau prospect
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->nom = $_POST['nom'];
            $this->prospect->prenom = $_POST['prenom'];
            $this->prospect->sexe = $_POST['sexe'];
            $this->prospect->telephone = $_POST['telephone'];
            $this->prospect->whatsapp = $_POST['whatsapp'];
            $this->prospect->email = $_POST['email'];
            $this->prospect->ville = $_POST['ville'];
            $this->prospect->niveau_academique = $_POST['niveau_academique'];
            $this->prospect->specialite_id = $_POST['specialite_id'];
            $this->prospect->source_id = $_POST['source_id'];
            $this->prospect->marketiste_id = $_POST['marketiste_id'];
            $this->prospect->statut = $_POST['statut'];
            $this->prospect->commentaire = $_POST['commentaire'];
            
            if($this->prospect->create()) {
                $_SESSION['success'] = "Prospect ajouté avec succès.";
                header("Location: /educrm/prospects");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du prospect.";
                header("Location: /educrm/prospects/create");
            }
            exit();
        }
    }
    
    // Afficher les détails d'un prospect
    public function show($id) {
        $this->prospect->id = $id;
        if($this->prospect->readOne()) {
            require_once 'views/prospects/show.php';
        } else {
            $_SESSION['error'] = "Prospect non trouvé.";
            header("Location: /educrm/prospects");
            exit();
        }
    }
    
    // Afficher le formulaire d'édition
    public function edit($id) {
        $this->prospect->id = $id;
        if($this->prospect->readOne()) {
            $specialites = $this->specialite->readAll()->fetchAll(PDO::FETCH_ASSOC);
            $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);
            $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
            require_once 'views/prospects/edit.php';
        } else {
            $_SESSION['error'] = "Prospect non trouvé.";
            header("Location: /educrm/prospects");
            exit();
        }
    }
    
    // Mettre à jour un prospect
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;
            $this->prospect->nom = $_POST['nom'];
            $this->prospect->prenom = $_POST['prenom'];
            $this->prospect->sexe = $_POST['sexe'];
            $this->prospect->telephone = $_POST['telephone'];
            $this->prospect->whatsapp = $_POST['whatsapp'];
            $this->prospect->email = $_POST['email'];
            $this->prospect->ville = $_POST['ville'];
            $this->prospect->niveau_academique = $_POST['niveau_academique'];
            $this->prospect->specialite_id = $_POST['specialite_id'];
            $this->prospect->source_id = $_POST['source_id'];
            $this->prospect->marketiste_id = $_POST['marketiste_id'];
            $this->prospect->statut = $_POST['statut'];
            $this->prospect->commentaire = $_POST['commentaire'];
            
            if($this->prospect->update()) {
                $_SESSION['success'] = "Prospect modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/prospects");
            exit();
        }
    }
    
    // Changer le statut
    public function changeStatut($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;
            $this->prospect->statut = $_POST['statut'];
            
            if($this->prospect->updateStatut()) {
                $_SESSION['success'] = "Statut modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du statut.";
            }
            header("Location: /educrm/prospects");
            exit();
        }
    }
    
    // Réaffecter à un marketiste
    public function reassignMarketiste($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;
            $this->prospect->marketiste_id = $_POST['marketiste_id'];
            
            if($this->prospect->reassignMarketiste()) {
                $_SESSION['success'] = "Prospect réaffecté avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la réaffectation.";
            }
            header("Location: /educrm/prospects");
            exit();
        }
    }
    
    // Réaffecter à une spécialité
    public function reassignSpecialite($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;
            $this->prospect->specialite_id = $_POST['specialite_id'];
            
            if($this->prospect->reassignSpecialite()) {
                $_SESSION['success'] = "Spécialité réaffectée avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la réaffectation.";
            }
            header("Location: /educrm/prospects");
            exit();
        }
    }
    
    // Exporter la liste
    public function export() {
        $filters = [
            'departement_id' => isset($_GET['departement_id']) ? $_GET['departement_id'] : null,
            'specialite_id' => isset($_GET['specialite_id']) ? $_GET['specialite_id'] : null,
            'source_id' => isset($_GET['source_id']) ? $_GET['source_id'] : null,
            'statut' => isset($_GET['statut']) ? $_GET['statut'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];
        
        $stmt = $this->prospect->export($filters);
        $prospects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Définir les en-têtes CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="prospects_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Ajouter le BOM pour UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // En-têtes des colonnes
        fputcsv($output, [
            'ID', 'Nom', 'Prénom', 'Sexe', 'Téléphone', 'WhatsApp', 'Email',
            'Ville', 'Niveau académique', 'Spécialité', 'Département', 'Source',
            'Marketiste', 'Statut', 'Date création'
        ]);
        
        // Données
        foreach($prospects as $prospect) {
            fputcsv($output, [
                $prospect['id'],
                $prospect['nom'],
                $prospect['prenom'],
                $prospect['sexe'],
                $prospect['telephone'],
                $prospect['whatsapp'],
                $prospect['email'],
                $prospect['ville'],
                $prospect['niveau_academique'],
                $prospect['specialite_nom'],
                $prospect['departement_nom'],
                $prospect['source_nom'],
                $prospect['marketiste_nom'],
                $prospect['statut'],
                $prospect['date_creation']
            ]);
        }
        
        fclose($output);
        exit();
    }
}
?>