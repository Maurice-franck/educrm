<?php
class Prospect {
    private $conn;
    private $table = "prospects";
    
    public $id;
    public $nom;
    public $prenom;
    public $sexe;
    public $telephone;
    public $whatsapp;
    public $email;
    public $ville;
    public $niveau_academique;
    public $specialite_id;
    public $source_id;
    public $marketiste_id;
    public $statut;
    public $commentaire;
    public $date_creation;
    public $departement_id;
    public $departement_nom;
    public $specialite_nom;
    public $marketiste_nom;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer un prospect
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET nom = :nom,
                      prenom = :prenom,
                      sexe = :sexe,
                      telephone = :telephone,
                      whatsapp = :whatsapp,
                      email = :email,
                      ville = :ville,
                      niveau_academique = :niveau_academique,
                      specialite_id = :specialite_id,
                      source_id = :source_id,
                      marketiste_id = :marketiste_id,
                      statut = :statut,
                      commentaire = :commentaire";
        
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->whatsapp = htmlspecialchars(strip_tags($this->whatsapp));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->ville = htmlspecialchars(strip_tags($this->ville));
        $this->niveau_academique = htmlspecialchars(strip_tags($this->niveau_academique));
        $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":sexe", $this->sexe);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":whatsapp", $this->whatsapp);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":ville", $this->ville);
        $stmt->bindParam(":niveau_academique", $this->niveau_academique);
        $stmt->bindParam(":specialite_id", $this->specialite_id);
        $stmt->bindParam(":source_id", $this->source_id);
        $stmt->bindParam(":marketiste_id", $this->marketiste_id);
        $stmt->bindParam(":statut", $this->statut);
        $stmt->bindParam(":commentaire", $this->commentaire);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lire tous les prospects avec filtres
    public function readAll($filters = []) {
        $query = "SELECT p.*, 
                  s.nom as specialite_nom,
                  sm.nom as source_nom,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom,
                  d.nom as departement_nom,
                  d.id as departement_id
                  FROM " . $this->table . " p
                  LEFT JOIN specialites s ON p.specialite_id = s.id
                  LEFT JOIN sources_marketing sm ON p.source_id = sm.id
                  LEFT JOIN utilisateurs u ON p.marketiste_id = u.id
                  LEFT JOIN departements d ON s.departement_id = d.id
                  WHERE 1=1";
        
        // Appliquer les filtres
        if(!empty($filters['departement_id'])) {
            $query .= " AND d.id = :departement_id";
        }
        if(!empty($filters['specialite_id'])) {
            $query .= " AND p.specialite_id = :specialite_id";
        }
        if(!empty($filters['source_id'])) {
            $query .= " AND p.source_id = :source_id";
        }
        if(!empty($filters['statut'])) {
            $query .= " AND p.statut = :statut";
        }
        if(!empty($filters['marketiste_id'])) {
            $query .= " AND p.marketiste_id = :marketiste_id";
        }
        if(!empty($filters['search'])) {
            $query .= " AND (p.nom LIKE :search 
                       OR p.prenom LIKE :search 
                       OR p.telephone LIKE :search 
                       OR p.email LIKE :search)";
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND DATE(p.date_creation) BETWEEN :date_debut AND :date_fin";
        }
        
        $query .= " ORDER BY p.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        
        // Lier les filtres
        if(!empty($filters['departement_id'])) {
            $stmt->bindParam(":departement_id", $filters['departement_id']);
        }
        if(!empty($filters['specialite_id'])) {
            $stmt->bindParam(":specialite_id", $filters['specialite_id']);
        }
        if(!empty($filters['source_id'])) {
            $stmt->bindParam(":source_id", $filters['source_id']);
        }
        if(!empty($filters['statut'])) {
            $stmt->bindParam(":statut", $filters['statut']);
        }
        if(!empty($filters['marketiste_id'])) {
            $stmt->bindParam(":marketiste_id", $filters['marketiste_id']);
        }
        if(!empty($filters['search'])) {
            $search = "%{$filters['search']}%";
            $stmt->bindParam(":search", $search);
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindParam(":date_debut", $filters['date_debut']);
            $stmt->bindParam(":date_fin", $filters['date_fin']);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Lire un prospect par ID
    public function readOne() {
        $query = "SELECT p.*, 
                  s.nom as specialite_nom,
                  sm.nom as source_nom,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom,
                  d.nom as departement_nom,
                  d.id as departement_id
                  FROM " . $this->table . " p
                  LEFT JOIN specialites s ON p.specialite_id = s.id
                  LEFT JOIN sources_marketing sm ON p.source_id = sm.id
                  LEFT JOIN utilisateurs u ON p.marketiste_id = u.id
                  LEFT JOIN departements d ON s.departement_id = d.id
                  WHERE p.id = :id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->sexe = $row['sexe'];
            $this->telephone = $row['telephone'];
            $this->whatsapp = $row['whatsapp'];
            $this->email = $row['email'];
            $this->ville = $row['ville'];
            $this->niveau_academique = $row['niveau_academique'];
            $this->specialite_id = $row['specialite_id'];
            $this->source_id = $row['source_id'];
            $this->marketiste_id = $row['marketiste_id'];
            $this->statut = $row['statut'];
            $this->commentaire = $row['commentaire'];
            $this->date_creation = $row['date_creation'];
            $this->departement_id = $row['departement_id'];
            $this->departement_nom = $row['departement_nom'];
            $this->specialite_nom = $row['specialite_nom'];
            $this->marketiste_nom = $row['marketiste_nom'];
            return true;
        }
        return false;
    }
    
    // Mettre à jour un prospect
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET nom = :nom,
                      prenom = :prenom,
                      sexe = :sexe,
                      telephone = :telephone,
                      whatsapp = :whatsapp,
                      email = :email,
                      ville = :ville,
                      niveau_academique = :niveau_academique,
                      specialite_id = :specialite_id,
                      source_id = :source_id,
                      marketiste_id = :marketiste_id,
                      statut = :statut,
                      commentaire = :commentaire
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->whatsapp = htmlspecialchars(strip_tags($this->whatsapp));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->ville = htmlspecialchars(strip_tags($this->ville));
        $this->niveau_academique = htmlspecialchars(strip_tags($this->niveau_academique));
        $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":sexe", $this->sexe);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":whatsapp", $this->whatsapp);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":ville", $this->ville);
        $stmt->bindParam(":niveau_academique", $this->niveau_academique);
        $stmt->bindParam(":specialite_id", $this->specialite_id);
        $stmt->bindParam(":source_id", $this->source_id);
        $stmt->bindParam(":marketiste_id", $this->marketiste_id);
        $stmt->bindParam(":statut", $this->statut);
        $stmt->bindParam(":commentaire", $this->commentaire);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Mettre à jour le statut
    public function updateStatut() {
        $query = "UPDATE " . $this->table . " SET statut = :statut WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":statut", $this->statut);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Réaffecter à un marketiste
    public function reassignMarketiste() {
        $query = "UPDATE " . $this->table . " SET marketiste_id = :marketiste_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":marketiste_id", $this->marketiste_id);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Réaffecter à une spécialité
    public function reassignSpecialite() {
        $query = "UPDATE " . $this->table . " SET specialite_id = :specialite_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":specialite_id", $this->specialite_id);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Exporter les prospects
    public function export($filters = []) {
        $query = "SELECT p.*, 
                  s.nom as specialite_nom,
                  sm.nom as source_nom,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom,
                  d.nom as departement_nom
                  FROM " . $this->table . " p
                  LEFT JOIN specialites s ON p.specialite_id = s.id
                  LEFT JOIN sources_marketing sm ON p.source_id = sm.id
                  LEFT JOIN utilisateurs u ON p.marketiste_id = u.id
                  LEFT JOIN departements d ON s.departement_id = d.id
                  WHERE 1=1";
        
        // Appliquer les mêmes filtres que readAll
        if(!empty($filters['departement_id'])) {
            $query .= " AND d.id = :departement_id";
        }
        if(!empty($filters['specialite_id'])) {
            $query .= " AND p.specialite_id = :specialite_id";
        }
        if(!empty($filters['source_id'])) {
            $query .= " AND p.source_id = :source_id";
        }
        if(!empty($filters['statut'])) {
            $query .= " AND p.statut = :statut";
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND DATE(p.date_creation) BETWEEN :date_debut AND :date_fin";
        }
        
        $query .= " ORDER BY p.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($filters['departement_id'])) {
            $stmt->bindParam(":departement_id", $filters['departement_id']);
        }
        if(!empty($filters['specialite_id'])) {
            $stmt->bindParam(":specialite_id", $filters['specialite_id']);
        }
        if(!empty($filters['source_id'])) {
            $stmt->bindParam(":source_id", $filters['source_id']);
        }
        if(!empty($filters['statut'])) {
            $stmt->bindParam(":statut", $filters['statut']);
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindParam(":date_debut", $filters['date_debut']);
            $stmt->bindParam(":date_fin", $filters['date_fin']);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Statistiques des prospects
    public function getStats() {
        $query = "SELECT 
                  COUNT(*) as total,
                  SUM(CASE WHEN statut = 'NOUVEAU' THEN 1 ELSE 0 END) as nouveau,
                  SUM(CASE WHEN statut = 'CONTACTE' THEN 1 ELSE 0 END) as contacte,
                  SUM(CASE WHEN statut = 'RELANCE' THEN 1 ELSE 0 END) as relance,
                  SUM(CASE WHEN statut = 'RDV_PROGRAMME' THEN 1 ELSE 0 END) as rdv,
                  SUM(CASE WHEN statut = 'INTERESSE' THEN 1 ELSE 0 END) as interesse,
                  SUM(CASE WHEN statut = 'INSCRIT' THEN 1 ELSE 0 END) as inscrit,
                  SUM(CASE WHEN statut = 'ABANDONNE' THEN 1 ELSE 0 END) as abandonne
                  FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>