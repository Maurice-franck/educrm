<?php
require_once __DIR__ . '/../../models/Prospect.php';
require_once __DIR__ . '/../../models/Specialite.php';
require_once __DIR__ . '/../../models/SourceMarketing.php';
require_once __DIR__ . '/../../models/Departement.php';
require_once __DIR__ . '/../../models/Utilisateur.php';

/**
 * Contrôleur Prospects - Espace Chef de Département
 * Visualisation élargie à TOUS les prospects du département
 * (ceux dont la spécialité appartient à $_SESSION['departement_id']),
 * peu importe quel marketiste les gère.
 * Pas de réaffectation entre marketistes ni d'export global ici
 * (toujours réservés à l'ADMIN) — seulement create/edit/changeStatut.
 */
class ChefDepartementProspectController {
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

    // Liste de tous les prospects du département
    public function index() {
        $departementId = $_SESSION['departement_id'];

        $filters = [
            'departement_id' => $departementId, // forcé : portée du département
            'specialite_id'  => isset($_GET['specialite_id']) ? $_GET['specialite_id'] : null,
            'source_id'      => isset($_GET['source_id']) ? $_GET['source_id'] : null,
            'marketiste_id'  => isset($_GET['marketiste_id']) ? $_GET['marketiste_id'] : null,
            'statut'         => isset($_GET['statut']) ? $_GET['statut'] : null,
            'search'         => isset($_GET['search']) ? $_GET['search'] : null,
            'date_debut'     => isset($_GET['date_debut']) ? $_GET['date_debut'] : null,
            'date_fin'       => isset($_GET['date_fin']) ? $_GET['date_fin'] : null
        ];

        $stmt = $this->prospect->readAll($filters);
        $prospects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Seules les spécialités du département du chef sont pertinentes pour le filtre
        $specialites = $this->specialite->readByDepartement($departementId)->fetchAll(PDO::FETCH_ASSOC);
        $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $marketistes = $this->getMarketistesDuDepartement($departementId);

        require __DIR__ . '/../../views/chef-departement/prospects/index.php';
    }

    // Formulaire d'ajout
    public function create() {
        $departementId = $_SESSION['departement_id'];
        // Seules les spécialités du département du chef sont proposées
        $specialites = $this->specialite->readByDepartement($departementId)->fetchAll(PDO::FETCH_ASSOC);
        $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/chef-departement/prospects/create.php';
    }

    // Enregistrer un nouveau prospect (affecté au chef lui-même, qui agit comme marketiste)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifier que la spécialité choisie appartient bien au département du chef
            $this->specialite->id = $_POST['specialite_id'];
            if (!$this->specialite->readOne() || $this->specialite->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Cette spécialité n'appartient pas à votre département.";
                header("Location: /educrm/chef-departement/prospects/create");
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
            $this->prospect->marketiste_id     = $_SESSION['user_id']; // forcé
            $this->prospect->statut            = $_POST['statut'];
            $this->prospect->commentaire       = $_POST['commentaire'];

            if ($this->prospect->create()) {
                $_SESSION['success'] = "Prospect ajouté avec succès.";
                header("Location: /educrm/chef-departement/prospects");
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du prospect.";
                header("Location: /educrm/chef-departement/prospects/create");
            }
            exit();
        }
    }

    // Détail d'un prospect (uniquement s'il appartient au département)
    public function show($id) {
        $this->prospect->id = $id;
        if ($this->prospect->readOne() && $this->prospect->departement_id == $_SESSION['departement_id']) {
            $prospect = $this->prospect;
            require __DIR__ . '/../../views/chef-departement/prospects/show.php';
        } else {
            $_SESSION['error'] = "Prospect non trouvé.";
            header("Location: /educrm/chef-departement/prospects");
            exit();
        }
    }

    // Formulaire d'édition (uniquement s'il appartient au département)
    public function edit($id) {
        $this->prospect->id = $id;
        if ($this->prospect->readOne() && $this->prospect->departement_id == $_SESSION['departement_id']) {
            $prospect = $this->prospect;
            $departementId = $_SESSION['departement_id'];
            $specialites = $this->specialite->readByDepartement($departementId)->fetchAll(PDO::FETCH_ASSOC);
            $sources = $this->source->readAll()->fetchAll(PDO::FETCH_ASSOC);
            require __DIR__ . '/../../views/chef-departement/prospects/edit.php';
        } else {
            $_SESSION['error'] = "Prospect non trouvé.";
            header("Location: /educrm/chef-departement/prospects");
            exit();
        }
    }

    // Mettre à jour (uniquement s'il appartient au département)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;

            if (!$this->prospect->readOne() || $this->prospect->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/chef-departement/prospects");
                exit();
            }

            // Vérifier que la nouvelle spécialité choisie reste dans le département du chef
            $this->specialite->id = $_POST['specialite_id'];
            if (!$this->specialite->readOne() || $this->specialite->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Cette spécialité n'appartient pas à votre département.";
                header("Location: /educrm/chef-departement/prospects/$id/edit");
                exit();
            }

            // Conserver le marketiste déjà affecté (le chef ne réaffecte pas ici)
            $marketisteIdActuel = $this->prospect->marketiste_id;

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
            $this->prospect->marketiste_id     = $marketisteIdActuel; // inchangé
            $this->prospect->statut            = $_POST['statut'];
            $this->prospect->commentaire       = $_POST['commentaire'];

            if ($this->prospect->update()) {
                $_SESSION['success'] = "Prospect modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: /educrm/chef-departement/prospects");
            exit();
        }
    }

    // Changer le statut (uniquement s'il appartient au département)
    public function changeStatut($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prospect->id = $id;

            if (!$this->prospect->readOne() || $this->prospect->departement_id != $_SESSION['departement_id']) {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /educrm/chef-departement/prospects");
                exit();
            }

            $this->prospect->statut = $_POST['statut'];

            if ($this->prospect->updateStatut()) {
                $_SESSION['success'] = "Statut modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du statut.";
            }
            header("Location: /educrm/chef-departement/prospects");
            exit();
        }
    }

    // Liste des marketistes ayant au moins un prospect dans ce département
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
