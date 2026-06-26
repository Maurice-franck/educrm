<?php
require_once __DIR__ . '/../../models/Relance.php';
require_once __DIR__ . '/../../models/Prospect.php';
require_once __DIR__ . '/../../models/Utilisateur.php';

/**
 * Contrôleur Supervision - Espace Marketiste
 * Affiche les statistiques et l'historique des relances
 * STRICTEMENT filtré sur le marketiste connecté ($_SESSION['user_id'])
 */
class MarketisteSupervisionController {
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
        require_once __DIR__ . '/../../config/database.php';
        $database = new Database();
        return $database->getConnection();
    }

    // Tableau de bord de supervision (mes relances uniquement)
    public function index() {
        $marketisteId = $_SESSION['user_id'];

        // Filtres complémentaires (la propriété marketiste_id est forcée)
        $filters = [
            'marketiste_id' => $marketisteId,
            'type_relance'  => isset($_GET['type_relance']) ? $_GET['type_relance'] : null,
            'resultat'      => isset($_GET['resultat']) ? $_GET['resultat'] : null,
            'date_debut'    => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'      => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];

        $stats = $this->relance->getStats($filters);

        // Dernières relances, filtrées sur ce marketiste uniquement
        $stmt = $this->relance->readAll($filters);
        $allRelances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastRelances = array_slice($allRelances, 0, 10);

        require __DIR__ . '/../../views/marketiste/supervision/index.php';
    }

    // Historique complet de mes relances
    public function all() {
        $marketisteId = $_SESSION['user_id'];

        $filters = [
            'marketiste_id' => $marketisteId,
            'type_relance'  => isset($_GET['type_relance']) ? $_GET['type_relance'] : null,
            'resultat'      => isset($_GET['resultat']) ? $_GET['resultat'] : null,
            'date_debut'    => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'      => isset($_GET['date_fin']) ? $_GET['date_fin'] : null,
            'date_relance'  => isset($_GET['date_relance']) ? $_GET['date_relance'] : null
        ];

        $stmt = $this->relance->readAll($filters);
        $relances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/marketiste/supervision/all.php';
    }

    // Formulaire d'ajout d'une relance
    public function create() {
        $marketisteId = $_SESSION['user_id'];

        // Le marketiste ne voit que ses propres prospects
        $prospects = $this->prospect->readAll(['marketiste_id' => $marketisteId])->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/marketiste/supervision/create.php';
    }

    // Enregistrer une nouvelle relance (toujours sous le compte du marketiste connecté)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->relance->prospect_id    = $_POST['prospect_id'];
            $this->relance->utilisateur_id = $_SESSION['user_id']; // forcé, jamais depuis le formulaire
            $this->relance->type_relance   = $_POST['type_relance'];
            $this->relance->resultat       = $_POST['resultat'];
            $this->relance->commentaire    = $_POST['commentaire'];
            $this->relance->date_relance   = date('Y-m-d H:i:s');

            if ($this->relance->create()) {
                $_SESSION['success'] = "Relance ajoutée avec succès.";
                header("Location: /educrm/marketiste/supervision");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de la relance.";
                header("Location: /educrm/marketiste/supervision/create");
            }
            exit();
        }
    }

    // Afficher le détail d'une relance (uniquement si elle m'appartient)
    public function show($id) {
        $this->relance->id = $id;
        if ($this->relance->readOne() && $this->relance->utilisateur_id == $_SESSION['user_id']) {
            $relance = $this->relance;
            require __DIR__ . '/../../views/marketiste/supervision/show.php';
        } else {
            $_SESSION['error'] = "Relance non trouvée.";
            header("Location: /educrm/marketiste/supervision");
            exit();
        }
    }

    // Formulaire d'édition (uniquement si elle m'appartient)
    public function edit($id) {
        $this->relance->id = $id;
        if ($this->relance->readOne() && $this->relance->utilisateur_id == $_SESSION['user_id']) {
            $relance = $this->relance;
            require __DIR__ . '/../../views/marketiste/supervision/edit.php';
        } else {
            $_SESSION['error'] = "Relance non trouvée.";
            header("Location: /educrm/marketiste/supervision");
            exit();
        }
    }

    // Mettre à jour une relance (uniquement si elle m'appartient)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->relance->id = $id;

            if (!$this->relance->readOne() || $this->relance->utilisateur_id != $_SESSION['user_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/marketiste/supervision");
                exit();
            }

            $this->relance->type_relance = $_POST['type_relance'];
            $this->relance->resultat     = $_POST['resultat'];
            $this->relance->commentaire  = $_POST['commentaire'];

            if ($this->relance->update()) {
                $_SESSION['success'] = "Relance modifiée avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/marketiste/supervision");
            exit();
        }
    }
}
