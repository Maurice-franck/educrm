<?php
require_once __DIR__ . '/../../models/RendezVous.php';
require_once __DIR__ . '/../../models/Prospect.php';
require_once __DIR__ . '/../../models/Utilisateur.php';
require_once __DIR__ . '/../../models/Departement.php';

/**
 * Contrôleur Rendez-vous - Espace Marketiste
 * STRICTEMENT filtré sur le marketiste connecté ($_SESSION['user_id'])
 */
class MarketisteRendezVousController {
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
        require_once __DIR__ . '/../../config/database.php';
        $database = new Database();
        return $database->getConnection();
    }

    // Tableau de bord de mes rendez-vous
    public function index() {
        $marketisteId = $_SESSION['user_id'];

        $filters = [
            'marketiste_id' => $marketisteId,
            'date_debut'    => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'      => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];

        $stats = $this->rendezVous->getStats($filters);
        $todayRdv = $this->rendezVous->getTodayRendezVous($marketisteId);
        $upcomingRdv = $this->rendezVous->getUpcomingRendezVous(10, $marketisteId);

        require __DIR__ . '/../../views/marketiste/rendezvous/index.php';
    }

    // Liste complète de mes rendez-vous
    public function all() {
        $marketisteId = $_SESSION['user_id'];

        $filters = [
            'marketiste_id' => $marketisteId,
            'statut'        => isset($_GET['statut']) ? $_GET['statut'] : null,
            'date_rdv'      => isset($_GET['date_rdv']) ? $_GET['date_rdv'] : null,
            'date_debut'    => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'      => isset($_GET['date_fin']) ? $_GET['date_fin'] : null,
            'search'        => isset($_GET['search']) ? $_GET['search'] : null
        ];

        $stmt = $this->rendezVous->readAll($filters);
        $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/marketiste/rendezvous/all.php';
    }

    // Calendrier de mes rendez-vous
    public function calendar() {
        $marketisteId = $_SESSION['user_id'];
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');

        $calendar = $this->rendezVous->getCalendar($year, $month, $marketisteId);
        $rendezVous = $calendar->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/marketiste/rendezvous/calendar.php';
    }

    // Formulaire d'ajout
    public function create() {
        $marketisteId = $_SESSION['user_id'];
        // Le marketiste ne propose que ses propres prospects
        $prospects = $this->prospect->readAll(['marketiste_id' => $marketisteId])->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/marketiste/rendezvous/create.php';
    }

    // Enregistrer un nouveau rendez-vous (toujours sous mon compte)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->prospect_id    = $_POST['prospect_id'];
            $this->rendezVous->utilisateur_id = $_SESSION['user_id']; // forcé
            $this->rendezVous->date_rdv       = $_POST['date_rdv'];
            $this->rendezVous->heure_rdv      = $_POST['heure_rdv'];
            $this->rendezVous->lieu           = $_POST['lieu'];
            $this->rendezVous->objet          = $_POST['objet'];
            $this->rendezVous->statut         = $_POST['statut'];
            $this->rendezVous->observation    = $_POST['observation'];

            if ($this->rendezVous->create()) {
                $_SESSION['success'] = "Rendez-vous ajouté avec succès.";
                header("Location: /educrm/marketiste/rendezvous");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du rendez-vous.";
                header("Location: /educrm/marketiste/rendezvous/create");
            }
            exit();
        }
    }

    // Détail d'un rendez-vous (uniquement si je suis le marketiste assigné)
    public function show($id) {
        $this->rendezVous->id = $id;
        if ($this->rendezVous->readOne() && $this->rendezVous->utilisateur_id == $_SESSION['user_id']) {
            $rendezVous = $this->rendezVous;
            require __DIR__ . '/../../views/marketiste/rendezvous/show.php';
        } else {
            $_SESSION['error'] = "Rendez-vous non trouvé.";
            header("Location: /educrm/marketiste/rendezvous");
            exit();
        }
    }

    // Formulaire d'édition (uniquement si je suis le marketiste assigné)
    public function edit($id) {
        $this->rendezVous->id = $id;
        if ($this->rendezVous->readOne() && $this->rendezVous->utilisateur_id == $_SESSION['user_id']) {
            $rendezVous = $this->rendezVous;
            $marketisteId = $_SESSION['user_id'];
            $prospects = $this->prospect->readAll(['marketiste_id' => $marketisteId])->fetchAll(PDO::FETCH_ASSOC);
            require __DIR__ . '/../../views/marketiste/rendezvous/edit.php';
        } else {
            $_SESSION['error'] = "Rendez-vous non trouvé.";
            header("Location: /educrm/marketiste/rendezvous");
            exit();
        }
    }

    // Mettre à jour (uniquement si je suis le marketiste assigné)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->id = $id;

            if (!$this->rendezVous->readOne() || $this->rendezVous->utilisateur_id != $_SESSION['user_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/marketiste/rendezvous");
                exit();
            }

            $this->rendezVous->date_rdv    = $_POST['date_rdv'];
            $this->rendezVous->heure_rdv   = $_POST['heure_rdv'];
            $this->rendezVous->lieu        = $_POST['lieu'];
            $this->rendezVous->objet       = $_POST['objet'];
            $this->rendezVous->statut      = $_POST['statut'];
            $this->rendezVous->observation = $_POST['observation'];

            if ($this->rendezVous->update()) {
                $_SESSION['success'] = "Rendez-vous modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/marketiste/rendezvous");
            exit();
        }
    }

    // Changer le statut (uniquement si je suis le marketiste assigné)
    public function changeStatut($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->id = $id;

            if (!$this->rendezVous->readOne() || $this->rendezVous->utilisateur_id != $_SESSION['user_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/marketiste/rendezvous");
                exit();
            }

            $this->rendezVous->statut = $_POST['statut'];

            if ($this->rendezVous->updateStatut()) {
                $_SESSION['success'] = "Statut du rendez-vous modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du statut.";
            }
            header("Location: /educrm/marketiste/rendezvous");
            exit();
        }
    }
}
