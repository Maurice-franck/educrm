<?php
require_once __DIR__ . '/../../models/Relance.php';
require_once __DIR__ . '/../../models/Prospect.php';
require_once __DIR__ . '/../../models/Utilisateur.php';

/**
 * Contrôleur Supervision - Espace Chef de Département
 * Mêmes fonctionnalités que l'espace Marketiste (créer/modifier relances),
 * mais la VISUALISATION porte sur TOUT le département ($_SESSION['departement_id']),
 * pas seulement sur les relances du chef lui-même.
 * La création de relance reste personnelle (le chef agit alors comme un marketiste).
 */
class ChefDepartementSupervisionController {
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

    // Tableau de bord de supervision (toutes les relances du département)
    public function index() {
        $departementId = $_SESSION['departement_id'];

        $filters = [
            'departement_id' => $departementId, // forcé : portée du département
            'marketiste_id'  => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'type_relance'   => isset($_GET['type_relance']) ? $_GET['type_relance'] : null,
            'resultat'       => isset($_GET['resultat']) ? $_GET['resultat'] : null,
            'date_debut'     => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'       => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];

        $stats = $this->relance->getStats($filters);

        $stmt = $this->relance->readAll($filters);
        $allRelances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastRelances = array_slice($allRelances, 0, 10);

        // Liste des marketistes du département (pour le filtre déroulant)
        $marketistes = $this->getMarketistesDuDepartement($departementId);

        require __DIR__ . '/../../views/chef-departement/supervision/index.php';
    }

    // Historique complet des relances du département
    public function all() {
        $departementId = $_SESSION['departement_id'];

        $filters = [
            'departement_id' => $departementId,
            'marketiste_id'  => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'type_relance'   => isset($_GET['type_relance']) ? $_GET['type_relance'] : null,
            'resultat'       => isset($_GET['resultat']) ? $_GET['resultat'] : null,
            'date_debut'     => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'       => isset($_GET['date_fin']) ? $_GET['date_fin'] : null,
            'date_relance'   => isset($_GET['date_relance']) ? $_GET['date_relance'] : null
        ];

        $stmt = $this->relance->readAll($filters);
        $relances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $marketistes = $this->getMarketistesDuDepartement($departementId);

        require __DIR__ . '/../../views/chef-departement/supervision/all.php';
    }

    // Formulaire d'ajout d'une relance (le chef agit en son nom propre)
    public function create() {
        $departementId = $_SESSION['departement_id'];

        // Le chef ne propose que les prospects de SON département
        $prospects = $this->prospect->readAll(['departement_id' => $departementId])->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/chef-departement/supervision/create.php';
    }

    // Enregistrer une nouvelle relance (sous le compte du chef connecté)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifier que le prospect appartient bien au département du chef
            $this->prospect->id = $_POST['prospect_id'];
            if (!$this->prospect->readOne() || $this->prospect->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Ce prospect n'appartient pas à votre département.";
                header("Location: /educrm/chef-departement/supervision/create");
                exit();
            }

            $this->relance->prospect_id    = $_POST['prospect_id'];
            $this->relance->utilisateur_id = $_SESSION['user_id']; // forcé
            $this->relance->type_relance   = $_POST['type_relance'];
            $this->relance->resultat       = $_POST['resultat'];
            $this->relance->commentaire    = $_POST['commentaire'];
            $this->relance->date_relance   = date('Y-m-d H:i:s');

            if ($this->relance->create()) {
                $_SESSION['success'] = "Relance ajoutée avec succès.";
                header("Location: /educrm/chef-departement/supervision");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de la relance.";
                header("Location: /educrm/chef-departement/supervision/create");
            }
            exit();
        }
    }

    // Afficher le détail d'une relance (uniquement si elle appartient au département)
    public function show($id) {
        $this->relance->id = $id;
        if ($this->relance->readOne() && $this->relance->departement_id == $_SESSION['departement_id']) {
            $relance = $this->relance;
            require __DIR__ . '/../../views/chef-departement/supervision/show.php';
        } else {
            $_SESSION['error'] = "Relance non trouvée.";
            header("Location: /educrm/chef-departement/supervision");
            exit();
        }
    }

    // Formulaire d'édition (uniquement si elle appartient au département)
    public function edit($id) {
        $this->relance->id = $id;
        if ($this->relance->readOne() && $this->relance->departement_id == $_SESSION['departement_id']) {
            $relance = $this->relance;
            require __DIR__ . '/../../views/chef-departement/supervision/edit.php';
        } else {
            $_SESSION['error'] = "Relance non trouvée.";
            header("Location: /educrm/chef-departement/supervision");
            exit();
        }
    }

    // Mettre à jour une relance (uniquement si elle appartient au département)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->relance->id = $id;

            if (!$this->relance->readOne() || $this->relance->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/chef-departement/supervision");
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
            header("Location: /educrm/chef-departement/supervision");
            exit();
        }
    }

    // Liste des marketistes appartenant au même département que le chef
    // (un marketiste n'a pas de departement_id propre : on déduit son département
    // via les spécialités des prospects qui lui sont affectés)
    private function getMarketistesDuDepartement($departementId) {
        $query = "SELECT DISTINCT u.id, u.nom, u.prenom
                  FROM utilisateurs u
                  JOIN prospects p ON p.marketiste_id = u.id
                  JOIN specialites s ON p.specialite_id = s.id
                  WHERE s.departement_id = :departement_id AND u.role = 'MARKETISTE'
                  ORDER BY u.nom, u.prenom";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':departement_id', $departementId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
