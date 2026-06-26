<?php
require_once __DIR__ . '/../../models/RendezVous.php';
require_once __DIR__ . '/../../models/Prospect.php';
require_once __DIR__ . '/../../models/Utilisateur.php';
require_once __DIR__ . '/../../models/Departement.php';

/**
 * Contrôleur Rendez-vous - Espace Chef de Département
 * Visualisation élargie à TOUT le département ($_SESSION['departement_id']).
 */
class ChefDepartementRendezVousController {
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

    // Tableau de bord des rendez-vous du département
    public function index() {
        $departementId = $_SESSION['departement_id'];

        $filters = [
            'departement_id' => $departementId,
            'date_debut'     => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'       => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];

        $stats = $this->rendezVous->getStats($filters);
        $todayRdv = $this->rendezVous->getTodayRendezVous(null, $departementId);
        $upcomingRdv = $this->rendezVous->getUpcomingRendezVous(10, null, $departementId);

        require __DIR__ . '/../../views/chef-departement/rendezvous/index.php';
    }

    // Liste complète des rendez-vous du département
    public function all() {
        $departementId = $_SESSION['departement_id'];

        $filters = [
            'departement_id' => $departementId,
            'marketiste_id'  => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'statut'         => isset($_GET['statut']) ? $_GET['statut'] : null,
            'date_rdv'       => isset($_GET['date_rdv']) ? $_GET['date_rdv'] : null,
            'date_debut'     => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'       => isset($_GET['date_fin']) ? $_GET['date_fin'] : null,
            'search'         => isset($_GET['search']) ? $_GET['search'] : null
        ];

        $stmt = $this->rendezVous->readAll($filters);
        $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/chef-departement/rendezvous/all.php';
    }

    // Calendrier des rendez-vous du département
    public function calendar() {
        $departementId = $_SESSION['departement_id'];
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');

        $calendar = $this->rendezVous->getCalendar($year, $month, null, $departementId);
        $rendezVous = $calendar->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/chef-departement/rendezvous/calendar.php';
    }

    // Formulaire d'ajout (le chef agit en son nom propre)
    public function create() {
        $departementId = $_SESSION['departement_id'];
        // Le chef ne propose que les prospects de SON département
        $prospects = $this->prospect->readAll(['departement_id' => $departementId])->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/chef-departement/rendezvous/create.php';
    }

    // Enregistrer un nouveau rendez-vous (sous le compte du chef connecté)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifier que le prospect appartient bien au département du chef
            $this->prospect->id = $_POST['prospect_id'];
            if (!$this->prospect->readOne() || $this->prospect->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Ce prospect n'appartient pas à votre département.";
                header("Location: /educrm/chef-departement/rendezvous/create");
                exit();
            }

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
                header("Location: /educrm/chef-departement/rendezvous");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du rendez-vous.";
                header("Location: /educrm/chef-departement/rendezvous/create");
            }
            exit();
        }
    }

    // Détail d'un rendez-vous (uniquement s'il appartient au département)
    public function show($id) {
        $this->rendezVous->id = $id;
        if ($this->rendezVous->readOne() && $this->rendezVous->departement_id == $_SESSION['departement_id']) {
            $rendezVous = $this->rendezVous;
            require __DIR__ . '/../../views/chef-departement/rendezvous/show.php';
        } else {
            $_SESSION['error'] = "Rendez-vous non trouvé.";
            header("Location: /educrm/chef-departement/rendezvous");
            exit();
        }
    }

    // Formulaire d'édition (uniquement s'il appartient au département)
    public function edit($id) {
        $this->rendezVous->id = $id;
        if ($this->rendezVous->readOne() && $this->rendezVous->departement_id == $_SESSION['departement_id']) {
            $rendezVous = $this->rendezVous;
            $departementId = $_SESSION['departement_id'];
            $prospects = $this->prospect->readAll(['departement_id' => $departementId])->fetchAll(PDO::FETCH_ASSOC);
            require __DIR__ . '/../../views/chef-departement/rendezvous/edit.php';
        } else {
            $_SESSION['error'] = "Rendez-vous non trouvé.";
            header("Location: /educrm/chef-departement/rendezvous");
            exit();
        }
    }

    // Mettre à jour (uniquement s'il appartient au département)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->id = $id;

            if (!$this->rendezVous->readOne() || $this->rendezVous->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/chef-departement/rendezvous");
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
            header("Location: /educrm/chef-departement/rendezvous");
            exit();
        }
    }

    // Changer le statut (uniquement s'il appartient au département)
    public function changeStatut($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rendezVous->id = $id;

            if (!$this->rendezVous->readOne() || $this->rendezVous->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/chef-departement/rendezvous");
                exit();
            }

            $this->rendezVous->statut = $_POST['statut'];

            if ($this->rendezVous->updateStatut()) {
                $_SESSION['success'] = "Statut du rendez-vous modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du statut.";
            }
            header("Location: /educrm/chef-departement/rendezvous");
            exit();
        }
    }
}
