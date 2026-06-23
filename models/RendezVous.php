<?php
class RendezVous {
    private $conn;
    private $table = "rendez_vous";
    
    public $id;
    public $prospect_id;
    public $utilisateur_id;
    public $date_rdv;
    public $heure_rdv;
    public $lieu;
    public $objet;
    public $statut;
    public $observation;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer un rendez-vous
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET prospect_id = :prospect_id,
                      utilisateur_id = :utilisateur_id,
                      date_rdv = :date_rdv,
                      heure_rdv = :heure_rdv,
                      lieu = :lieu,
                      objet = :objet,
                      statut = :statut,
                      observation = :observation";
        
        $stmt = $this->conn->prepare($query);
        
        $this->prospect_id = htmlspecialchars(strip_tags($this->prospect_id));
        $this->utilisateur_id = htmlspecialchars(strip_tags($this->utilisateur_id));
        $this->lieu = htmlspecialchars(strip_tags($this->lieu));
        $this->objet = htmlspecialchars(strip_tags($this->objet));
        $this->observation = htmlspecialchars(strip_tags($this->observation));
        
        $stmt->bindParam(":prospect_id", $this->prospect_id);
        $stmt->bindParam(":utilisateur_id", $this->utilisateur_id);
        $stmt->bindParam(":date_rdv", $this->date_rdv);
        $stmt->bindParam(":heure_rdv", $this->heure_rdv);
        $stmt->bindParam(":lieu", $this->lieu);
        $stmt->bindParam(":objet", $this->objet);
        $stmt->bindParam(":statut", $this->statut);
        $stmt->bindParam(":observation", $this->observation);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lire tous les rendez-vous avec filtres
    public function readAll($filters = []) {
        $query = "SELECT r.*, 
                  CONCAT(p.nom, ' ', p.prenom) as prospect_nom,
                  p.telephone as prospect_telephone,
                  p.email as prospect_email,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom,
                  s.nom as specialite_nom,
                  d.nom as departement_nom,
                  d.id as departement_id
                  FROM " . $this->table . " r
                  LEFT JOIN prospects p ON r.prospect_id = p.id
                  LEFT JOIN utilisateurs u ON r.utilisateur_id = u.id
                  LEFT JOIN specialites s ON p.specialite_id = s.id
                  LEFT JOIN departements d ON s.departement_id = d.id
                  WHERE 1=1";
        
        // Appliquer les filtres
        if(!empty($filters['departement_id'])) {
            $query .= " AND d.id = :departement_id";
        }
        if(!empty($filters['marketiste_id'])) {
            $query .= " AND r.utilisateur_id = :marketiste_id";
        }
        if(!empty($filters['statut'])) {
            $query .= " AND r.statut = :statut";
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND r.date_rdv BETWEEN :date_debut AND :date_fin";
        }
        if(!empty($filters['date_rdv'])) {
            $query .= " AND r.date_rdv = :date_rdv";
        }
        if(!empty($filters['search'])) {
            $query .= " AND (CONCAT(p.nom, ' ', p.prenom) LIKE :search 
                       OR p.telephone LIKE :search)";
        }
        
        $query .= " ORDER BY r.date_rdv ASC, r.heure_rdv ASC";
        
        $stmt = $this->conn->prepare($query);
        
        // Lier les filtres
        if(!empty($filters['departement_id'])) {
            $stmt->bindParam(":departement_id", $filters['departement_id']);
        }
        if(!empty($filters['marketiste_id'])) {
            $stmt->bindParam(":marketiste_id", $filters['marketiste_id']);
        }
        if(!empty($filters['statut'])) {
            $stmt->bindParam(":statut", $filters['statut']);
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindParam(":date_debut", $filters['date_debut']);
            $stmt->bindParam(":date_fin", $filters['date_fin']);
        }
        if(!empty($filters['date_rdv'])) {
            $stmt->bindParam(":date_rdv", $filters['date_rdv']);
        }
        if(!empty($filters['search'])) {
            $search = "%{$filters['search']}%";
            $stmt->bindParam(":search", $search);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Lire un rendez-vous par ID
    public function readOne() {
        $query = "SELECT r.*, 
                  CONCAT(p.nom, ' ', p.prenom) as prospect_nom,
                  p.telephone as prospect_telephone,
                  p.email as prospect_email,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom
                  FROM " . $this->table . " r
                  LEFT JOIN prospects p ON r.prospect_id = p.id
                  LEFT JOIN utilisateurs u ON r.utilisateur_id = u.id
                  WHERE r.id = :id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->prospect_id = $row['prospect_id'];
            $this->utilisateur_id = $row['utilisateur_id'];
            $this->date_rdv = $row['date_rdv'];
            $this->heure_rdv = $row['heure_rdv'];
            $this->lieu = $row['lieu'];
            $this->objet = $row['objet'];
            $this->statut = $row['statut'];
            $this->observation = $row['observation'];
            return true;
        }
        return false;
    }
    
    // Mettre à jour un rendez-vous
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET date_rdv = :date_rdv,
                      heure_rdv = :heure_rdv,
                      lieu = :lieu,
                      objet = :objet,
                      statut = :statut,
                      observation = :observation
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->lieu = htmlspecialchars(strip_tags($this->lieu));
        $this->objet = htmlspecialchars(strip_tags($this->objet));
        $this->observation = htmlspecialchars(strip_tags($this->observation));
        
        $stmt->bindParam(":date_rdv", $this->date_rdv);
        $stmt->bindParam(":heure_rdv", $this->heure_rdv);
        $stmt->bindParam(":lieu", $this->lieu);
        $stmt->bindParam(":objet", $this->objet);
        $stmt->bindParam(":statut", $this->statut);
        $stmt->bindParam(":observation", $this->observation);
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
    
    // Supprimer un rendez-vous
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Statistiques des rendez-vous
    public function getStats($filters = []) {
        $query = "SELECT 
                  COUNT(*) as total,
                  SUM(CASE WHEN statut = 'PLANIFIE' THEN 1 ELSE 0 END) as planifie,
                  SUM(CASE WHEN statut = 'CONFIRME' THEN 1 ELSE 0 END) as confirme,
                  SUM(CASE WHEN statut = 'REALISE' THEN 1 ELSE 0 END) as realise,
                  SUM(CASE WHEN statut = 'ANNULE' THEN 1 ELSE 0 END) as annule,
                  SUM(CASE WHEN statut = 'REPORTE' THEN 1 ELSE 0 END) as reporte,
                  SUM(CASE WHEN date_rdv >= CURDATE() AND statut IN ('PLANIFIE', 'CONFIRME') THEN 1 ELSE 0 END) as a_venir
                  FROM " . $this->table . " r
                  WHERE 1=1";
        
        if(!empty($filters['departement_id'])) {
            $query .= " AND EXISTS (SELECT 1 FROM prospects p 
                       LEFT JOIN specialites s ON p.specialite_id = s.id 
                       WHERE p.id = r.prospect_id AND s.departement_id = :departement_id)";
        }
        if(!empty($filters['marketiste_id'])) {
            $query .= " AND r.utilisateur_id = :marketiste_id";
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND r.date_rdv BETWEEN :date_debut AND :date_fin";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($filters['departement_id'])) {
            $stmt->bindParam(":departement_id", $filters['departement_id']);
        }
        if(!empty($filters['marketiste_id'])) {
            $stmt->bindParam(":marketiste_id", $filters['marketiste_id']);
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindParam(":date_debut", $filters['date_debut']);
            $stmt->bindParam(":date_fin", $filters['date_fin']);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Statistiques par marketiste
    public function getStatsByMarketiste($filters = []) {
        $query = "SELECT 
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom,
                  u.id as marketiste_id,
                  COUNT(r.id) as total_rdv,
                  SUM(CASE WHEN r.statut = 'PLANIFIE' THEN 1 ELSE 0 END) as planifie,
                  SUM(CASE WHEN r.statut = 'CONFIRME' THEN 1 ELSE 0 END) as confirme,
                  SUM(CASE WHEN r.statut = 'REALISE' THEN 1 ELSE 0 END) as realise,
                  SUM(CASE WHEN r.statut = 'ANNULE' THEN 1 ELSE 0 END) as annule
                  FROM " . $this->table . " r
                  JOIN utilisateurs u ON r.utilisateur_id = u.id
                  WHERE 1=1";
        
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND r.date_rdv BETWEEN :date_debut AND :date_fin";
        }
        
        $query .= " GROUP BY r.utilisateur_id ORDER BY total_rdv DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindParam(":date_debut", $filters['date_debut']);
            $stmt->bindParam(":date_fin", $filters['date_fin']);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Rendez-vous du jour
    public function getTodayRendezVous() {
        $query = "SELECT r.*, 
                  CONCAT(p.nom, ' ', p.prenom) as prospect_nom,
                  p.telephone as prospect_telephone,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom
                  FROM " . $this->table . " r
                  LEFT JOIN prospects p ON r.prospect_id = p.id
                  LEFT JOIN utilisateurs u ON r.utilisateur_id = u.id
                  WHERE r.date_rdv = CURDATE()
                  ORDER BY r.heure_rdv ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Rendez-vous à venir
    public function getUpcomingRendezVous($limit = 10) {
        $query = "SELECT r.*, 
                  CONCAT(p.nom, ' ', p.prenom) as prospect_nom,
                  p.telephone as prospect_telephone,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom
                  FROM " . $this->table . " r
                  LEFT JOIN prospects p ON r.prospect_id = p.id
                  LEFT JOIN utilisateurs u ON r.utilisateur_id = u.id
                  WHERE r.date_rdv >= CURDATE() AND r.statut IN ('PLANIFIE', 'CONFIRME')
                  ORDER BY r.date_rdv ASC, r.heure_rdv ASC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    
    // Calendrier des rendez-vous par mois
    public function getCalendar($year, $month) {
        $query = "SELECT r.*, 
                  CONCAT(p.nom, ' ', p.prenom) as prospect_nom,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom
                  FROM " . $this->table . " r
                  LEFT JOIN prospects p ON r.prospect_id = p.id
                  LEFT JOIN utilisateurs u ON r.utilisateur_id = u.id
                  WHERE YEAR(r.date_rdv) = :year AND MONTH(r.date_rdv) = :month
                  ORDER BY r.date_rdv ASC, r.heure_rdv ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":year", $year);
        $stmt->bindParam(":month", $month);
        $stmt->execute();
        return $stmt;
    }
}
?>