<?php
require_once __DIR__ . '/../../models/Prospect.php';
require_once __DIR__ . '/../../models/Specialite.php';
require_once __DIR__ . '/../../models/SourceMarketing.php';
require_once __DIR__ . '/../../models/Departement.php';
require_once __DIR__ . '/../../models/Utilisateur.php';

/**
 * Contrôleur Prospects - Espace Marketiste
 * STRICTEMENT filtré sur le marketiste connecté ($_SESSION['user_id'])
 * Pas de réaffectation (réservée à l'ADMIN/CHEF_DEPARTEMENT) ni d'export global.
 */
class MarketisteProspectController {
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
        require_once __DIR__ . '/../../config/database.php';
        $database = new Database();
        return $database->getConnection();
    }

    // Liste de mes prospects uniquement
    public function index() {
        $marketisteId = $_SESSION['user_id'];

        $filters = [
            'marketiste_id'  => $marketisteId, // forcé : jamais modifiable par l'URL
            'departement_id' => isset($_GET['departement_id']) ? $_GET['departement_id'] : null,
            'specialite_id'  => isset($_GET['specialite_id']) ? $_GET['specialite_id'] : null,
            'source_id'      => isset($_GET['source_id']) ? $_GET['source_id'] : null,
            'statut'         => isset($_GET['statut']) ? $_GET['statut'] : null,
            'search'         => isset($_GET['search']) ? $_GET['search'] : null,
            'date_debut'     => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'       => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];

        $stmt = $this->prospect->readAll($filters);
        $prospects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $departements = $this->departement->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $specialites = $this->specialite->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/marketiste/prospects/index.php';
    }

    // Formulaire d'ajout
    public function create() {
        $specialites = $this->specialite->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/marketiste/prospects/create.php';
    }

    // Enregistrer un nouveau prospect (toujours affecté à moi-même)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->nom               = $_POST['nom'];
            $this->prospect->prenom            = $_POST['prenom'];
            $this->prospect->sexe              = $_POST['sexe'];
            $this->prospect->telephone         = $_POST['telephone'];
            $this->prospect->whatsapp          = $_POST['whatsapp'];
            $this->prospect->email             = $_POST['email'];
            $this->prospect->ville             = $_POST['ville'];
            $this->prospect->niveau_academique = $_POST['niveau_academique'];
            $this->prospect->specialite_id     = $_POST['specialite_id'];
            $this->prospect->source_id         = $_POST['source_id'];
            $this->prospect->marketiste_id     = $_SESSION['user_id']; // forcé
            $this->prospect->statut            = $_POST['statut'];
            $this->prospect->commentaire       = $_POST['commentaire'];

            if ($this->prospect->create()) {
                $_SESSION['success'] = "Prospect ajouté avec succès.";
                header("Location: /educrm/marketiste/prospects");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du prospect.";
                header("Location: /educrm/marketiste/prospects/create");
            }
            exit();
        }
    }

    // Détail d'un prospect (uniquement si je suis le marketiste assigné)
    public function show($id) {
        $this->prospect->id = $id;
        if ($this->prospect->readOne() && $this->prospect->marketiste_id == $_SESSION['user_id']) {
            $prospect = $this->prospect;
            require __DIR__ . '/../../views/marketiste/prospects/show.php';
        } else {
            $_SESSION['error'] = "Prospect non trouvé.";
            header("Location: /educrm/marketiste/prospects");
            exit();
        }
    }

    // Formulaire d'édition (uniquement si je suis le marketiste assigné)
    public function edit($id) {
        $this->prospect->id = $id;
        if ($this->prospect->readOne() && $this->prospect->marketiste_id == $_SESSION['user_id']) {
            $prospect = $this->prospect;
            $specialites = $this->specialite->readAll()->fetchAll(PDO::FETCH_ASSOC);
            $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);
            require __DIR__ . '/../../views/marketiste/prospects/edit.php';
        } else {
            $_SESSION['error'] = "Prospect non trouvé.";
            header("Location: /educrm/marketiste/prospects");
            exit();
        }
    }

    // Mettre à jour (uniquement si je suis le marketiste assigné)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;

            if (!$this->prospect->readOne() || $this->prospect->marketiste_id != $_SESSION['user_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/marketiste/prospects");
                exit();
            }

            $this->prospect->nom               = $_POST['nom'];
            $this->prospect->prenom            = $_POST['prenom'];
            $this->prospect->sexe              = $_POST['sexe'];
            $this->prospect->telephone         = $_POST['telephone'];
            $this->prospect->whatsapp          = $_POST['whatsapp'];
            $this->prospect->email             = $_POST['email'];
            $this->prospect->ville             = $_POST['ville'];
            $this->prospect->niveau_academique = $_POST['niveau_academique'];
            $this->prospect->specialite_id     = $_POST['specialite_id'];
            $this->prospect->source_id         = $_POST['source_id'];
            $this->prospect->marketiste_id     = $_SESSION['user_id']; // reste inchangé, forcé
            $this->prospect->statut            = $_POST['statut'];
            $this->prospect->commentaire       = $_POST['commentaire'];

            if ($this->prospect->update()) {
                $_SESSION['success'] = "Prospect modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/marketiste/prospects");
            exit();
        }
    }

    // Changer le statut (uniquement si je suis le marketiste assigné)
    public function changeStatut($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;

            if (!$this->prospect->readOne() || $this->prospect->marketiste_id != $_SESSION['user_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/marketiste/prospects");
                exit();
            }

            $this->prospect->statut = $_POST['statut'];

            if ($this->prospect->updateStatut()) {
                $_SESSION['success'] = "Statut modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du statut.";
            }
            header("Location: /educrm/marketiste/prospects");
            exit();
        }
    }
}
