<?php
require_once 'models/RendezVous.php';
require_once 'models/Prospect.php';
require_once 'models/Utilisateur.php';
require_once 'models/Departement.php';

class RendezVousController {
    private $db;
    private $rendezVous;
    private $prospect;
    private $utilisateur;
    private $departement;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->rendezVous = new RendezVous($this->db);
        $this->prospect = new Prospect($this->db);
        $this->utilisateur = new Utilisateur($this->db);
        $this->departement = new Departement($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher le tableau de bord des rendez-vous
    public function index() {
        // Récupérer les filtres
        $filters = [
            'departement_id' => isset($_GET['departement_id']) ? $_GET['departement_id'] : null,
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];
        
        // Récupérer les statistiques
        $stats = $this->rendezVous->getStats($filters);
        $statsByMarketiste = $this->rendezVous->getStatsByMarketiste($filters);
        $todayRdv = $this->rendezVous->getTodayRendezVous();
        $upcomingRdv = $this->rendezVous->getUpcomingRendezVous(10);
        
        // Récupérer les données pour les filtres
        $departements = $this->departement->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/rendezvous/index.php';
    }
    
    // Afficher tous les rendez-vous
    public function all() {
        $filters = [
            'departement_id' => isset($_GET['departement_id']) ? $_GET['departement_id'] : null,
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'statut' => isset($_GET['statut']) ? $_GET['statut'] : null,
            'date_rdv' => isset($_GET['date_rdv']) ? $_GET['date_rdv'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null,
            'search' => isset($_GET['search']) ? $_GET['search'] : null
        ];
        
        $stmt = $this->rendezVous->readAll($filters);
        $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer les données pour les filtres
        $departements = $this->departement->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/rendezvous/all.php';
    }
    
    // Afficher le formulaire d'ajout
    public function create() {
        $prospects = $this->prospect->readAll([])->fetchAll(PDO::FETCH_ASSOC);
        $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/rendezvous/create.php';
    }
    
    // Enregistrer un nouveau rendez-vous
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->prospect_id = $_POST['prospect_id'];
            $this->rendezVous->utilisateur_id = $_POST['utilisateur_id'];
            $this->rendezVous->date_rdv = $_POST['date_rdv'];
            $this->rendezVous->heure_rdv = $_POST['heure_rdv'];
            $this->rendezVous->lieu = $_POST['lieu'];
            $this->rendezVous->objet = $_POST['objet'];
            $this->rendezVous->statut = $_POST['statut'];
            $this->rendezVous->observation = $_POST['observation'];
            
            if($this->rendezVous->create()) {
                $_SESSION['success'] = "Rendez-vous ajouté avec succès.";
                header("Location: /educrm/rendezvous");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du rendez-vous.";
                header("Location: /educrm/rendezvous/create");
            }
            exit();
        }
    }
    
    // Afficher les détails d'un rendez-vous
    public function show($id) {
        $this->rendezVous->id = $id;
        if($this->rendezVous->readOne()) {
            require_once 'views/rendezvous/show.php';
        } else {
            $_SESSION['error'] = "Rendez-vous non trouvé.";
            header("Location: /educrm/rendezvous");
            exit();
        }
    }
    
    // Afficher le formulaire d'édition
    public function edit($id) {
        $this->rendezVous->id = $id;
        if($this->rendezVous->readOne()) {
            $prospects = $this->prospect->readAll([])->fetchAll(PDO::FETCH_ASSOC);
            $marketistes = $this->utilisateur->readAll()->fetchAll(PDO::FETCH_ASSOC);
            require_once 'views/rendezvous/edit.php';
        } else {
            $_SESSION['error'] = "Rendez-vous non trouvé.";
            header("Location: /educrm/rendezvous");
            exit();
        }
    }
    
    // Mettre à jour un rendez-vous
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->id = $id;
            $this->rendezVous->date_rdv = $_POST['date_rdv'];
            $this->rendezVous->heure_rdv = $_POST['heure_rdv'];
            $this->rendezVous->lieu = $_POST['lieu'];
            $this->rendezVous->objet = $_POST['objet'];
            $this->rendezVous->statut = $_POST['statut'];
            $this->rendezVous->observation = $_POST['observation'];
            
            if($this->rendezVous->update()) {
                $_SESSION['success'] = "Rendez-vous modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/rendezvous");
            exit();
        }
    }
    
    // Changer le statut
    public function changeStatut($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->id = $id;
            $this->rendezVous->statut = $_POST['statut'];
            
            if($this->rendezVous->updateStatut()) {
                $_SESSION['success'] = "Statut du rendez-vous modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du statut.";
            }
            header("Location: /educrm/rendezvous");
            exit();
        }
    }
    
    // Supprimer un rendez-vous
    public function delete($id) {
        $this->rendezVous->id = $id;
        
        if($this->rendezVous->delete()) {
            $_SESSION['success'] = "Rendez-vous supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header("Location: /educrm/rendezvous");
        exit();
    }
    
    // Calendrier
    public function calendar() {
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        
        $calendar = $this->rendezVous->getCalendar($year, $month);
        $rendezVous = $calendar->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/rendezvous/calendar.php';
    }
    
    // Export des rendez-vous
    public function export() {
        $filters = [
            'departement_id' => isset($_GET['departement_id']) ? $_GET['departement_id'] : null,
            'marketiste_id' => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'statut' => isset($_GET['statut']) ? $_GET['statut'] : null,
            'date_debut' => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin' => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];
        
        $stmt = $this->rendezVous->readAll($filters);
        $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="rendezvous_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            'ID', 'Date', 'Heure', 'Prospect', 'Téléphone', 'Marketiste',
            'Département', 'Spécialité', 'Lieu', 'Objet', 'Statut', 'Observation'
        ]);
        
        foreach($rendezVous as $rdv) {
            fputcsv($output, [
                $rdv['id'],
                $rdv['date_rdv'],
                $rdv['heure_rdv'],
                $rdv['prospect_nom'],
                $rdv['prospect_telephone'],
                $rdv['marketiste_nom'],
                $rdv['departement_nom'],
                $rdv['specialite_nom'],
                $rdv['lieu'],
                $rdv['objet'],
                $rdv['statut'],
                $rdv['observation']
            ]);
        }
        
        fclose($output);
        exit();
    }
}
?>