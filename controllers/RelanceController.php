<?php
require_once 'models/Relance.php';
require_once 'models/Prospect.php';
require_once 'models/Utilisateur.php';

class RelanceController {
    private $db;
    private $relance;
    private $prospect;
    private $utilisateur;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->relance = new Relance($this->db);
        $this->prospect = new Prospect($this->db);
        $this->utilisateur = new Utilisateur($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher le tableau de bord des relances
    public function index() {
        // Récupérer les filtres
        $filters = [
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'type_relance' => isset($_GET['type_relance']) ? $_GET['type_relance'] : null,
            'resultat' => isset($_GET['resultat']) ? $_GET['resultat'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];
        
        // Récupérer les statistiques
        $stats = $this->relance->getStats($filters);
        $statsByMarketiste = $this->relance->getStatsByMarketiste($filters);
        $statsByPeriod = $this->relance->getStatsByPeriod('month');
        $lastRelances = $this->relance->getLastRelances(10);
        
        // Récupérer les marketistes pour le filtre
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/relances/index.php';
    }
    
    // Afficher toutes les relances
    public function all() {
        $filters = [
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'type_relance' => isset($_GET['type_relance']) ? $_GET['type_relance'] : null,
            'resultat' => isset($_GET['resultat']) ? $_GET['resultat'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null,
            'date_relance' => isset($_GET['date_relance']) ? $_GET['date_relance'] : null
        ];
        
        $stmt = $this->relance->readAll($filters);
        $relances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer les marketistes pour le filtre
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/relances/all.php';
    }
    
    // Afficher le formulaire d'ajout
    public function create() {
        $prospects = $this->prospect->readAll([])->fetchAll(PDO::FETCH_ASSOC);
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/relances/create.php';
    }
    
    // Enregistrer une nouvelle relance
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->relance->prospect_id = $_POST['prospect_id'];
            $this->relance->utilisateur_id = $_POST['utilisateur_id'];
            $this->relance->type_relance = $_POST['type_relance'];
            $this->relance->resultat = $_POST['resultat'];
            $this->relance->commentaire = $_POST['commentaire'];
            $this->relance->date_relance = date('Y-m-d H:i:s');
            
            if($this->relance->create()) {
                $_SESSION['success'] = "Relance ajoutée avec succès.";
                header("Location: /educrm/relances");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de la relance.";
                header("Location: /educrm/relances/create");
            }
            exit();
        }
    }
    
    // Afficher les détails d'une relance
    public function show($id) {
        $this->relance->id = $id;
        if($this->relance->readOne()) {
            require_once 'views/relances/show.php';
        } else {
            $_SESSION['error'] = "Relance non trouvée.";
            header("Location: /educrm/relances");
            exit();
        }
    }
    
    // Afficher le formulaire d'édition
    public function edit($id) {
        $this->relance->id = $id;
        if($this->relance->readOne()) {
            require_once 'views/relances/edit.php';
        } else {
            $_SESSION['error'] = "Relance non trouvée.";
            header("Location: /educrm/relances");
            exit();
        }
    }
    
    // Mettre à jour une relance
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->relance->id = $id;
            $this->relance->type_relance = $_POST['type_relance'];
            $this->relance->resultat = $_POST['resultat'];
            $this->relance->commentaire = $_POST['commentaire'];
            
            if($this->relance->update()) {
                $_SESSION['success'] = "Relance modifiée avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/relances");
            exit();
        }
    }
    
    // Supprimer une relance
    public function delete($id) {
        $this->relance->id = $id;
        
        if($this->relance->delete()) {
            $_SESSION['success'] = "Relance supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header("Location: /educrm/relances");
        exit();
    }
    
    // Rapport détaillé
    public function report() {
        $filters = [
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];
        
        $stats = $this->relance->getStats($filters);
        $statsByMarketiste = $this->relance->getStatsByMarketiste($filters);
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/relances/report.php';
    }
    
    // Export des relances
    public function export() {
        $filters = [
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'type_relance' => isset($_GET['type_relance']) ? $_GET['type_relance'] : null,
            'resultat' => isset($_GET['resultat']) ? $_GET['resultat'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];
        
        $stmt = $this->relance->readAll($filters);
        $relances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="relances_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            'ID', 'Prospect', 'Téléphone', 'Marketiste', 'Type', 'Résultat',
            'Commentaire', 'Date relance'
        ]);
        
        foreach($relances as $relance) {
            fputcsv($output, [
                $relance['id'],
                $relance['prospect_nom'],
                $relance['prospect_telephone'],
                $relance['marketiste_nom'],
                $relance['type_relance'],
                $relance['resultat'],
                $relance['commentaire'],
                $relance['date_relance']
            ]);
        }
        
        fclose($output);
        exit();
    }
}
?>