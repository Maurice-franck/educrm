<?php
class Relance {
    private $conn;
    private $table = "relances";
    
    public $id;
    public $prospect_id;
    public $utilisateur_id;
    public $type_relance;
    public $resultat;
    public $commentaire;
    public $date_relance;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer une relance
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET prospect_id = :prospect_id,
                      utilisateur_id = :utilisateur_id,
                      type_relance = :type_relance,
                      resultat = :resultat,
                      commentaire = :commentaire,
                      date_relance = :date_relance";
        
        $stmt = $this->conn->prepare($query);
        
        $this->prospect_id = htmlspecialchars(strip_tags($this->prospect_id));
        $this->utilisateur_id = htmlspecialchars(strip_tags($this->utilisateur_id));
        $this->type_relance = htmlspecialchars(strip_tags($this->type_relance));
        $this->resultat = htmlspecialchars(strip_tags($this->resultat));
        $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
        
        $stmt->bindParam(":prospect_id", $this->prospect_id);
        $stmt->bindParam(":utilisateur_id", $this->utilisateur_id);
        $stmt->bindParam(":type_relance", $this->type_relance);
        $stmt->bindParam(":resultat", $this->resultat);
        $stmt->bindParam(":commentaire", $this->commentaire);
        $stmt->bindParam(":date_relance", $this->date_relance);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lire toutes les relances avec filtres
    public function readAll($filters = []) {
        $query = "SELECT r.*, 
                  CONCAT(p.nom, ' ', p.prenom) as prospect_nom,
                  p.telephone as prospect_telephone,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom,
                  s.nom as specialite_nom
                  FROM " . $this->table . " r
                  LEFT JOIN prospects p ON r.prospect_id = p.id
                  LEFT JOIN utilisateurs u ON r.utilisateur_id = u.id
                  LEFT JOIN specialites s ON p.specialite_id = s.id
                  WHERE 1=1";
        
        // Appliquer les filtres
        if(!empty($filters['marketiste_id'])) {
            $query .= " AND r.utilisateur_id = :marketiste_id";
        }
        if(!empty($filters['type_relance'])) {
            $query .= " AND r.type_relance = :type_relance";
        }
        if(!empty($filters['resultat'])) {
            $query .= " AND r.resultat = :resultat";
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND DATE(r.date_relance) BETWEEN :date_debut AND :date_fin";
        }
        if(!empty($filters['date_relance'])) {
            $query .= " AND DATE(r.date_relance) = :date_relance";
        }
        
        $query .= " ORDER BY r.date_relance DESC";
        
        $stmt = $this->conn->prepare($query);
        
        // Lier les filtres
        if(!empty($filters['marketiste_id'])) {
            $stmt->bindParam(":marketiste_id", $filters['marketiste_id']);
        }
        if(!empty($filters['type_relance'])) {
            $stmt->bindParam(":type_relance", $filters['type_relance']);
        }
        if(!empty($filters['resultat'])) {
            $stmt->bindParam(":resultat", $filters['resultat']);
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindParam(":date_debut", $filters['date_debut']);
            $stmt->bindParam(":date_fin", $filters['date_fin']);
        }
        if(!empty($filters['date_relance'])) {
            $stmt->bindParam(":date_relance", $filters['date_relance']);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Statistiques des relances
    public function getStats($filters = []) {
        $query = "SELECT 
                  COUNT(*) as total,
                  SUM(CASE WHEN type_relance = 'APPEL' THEN 1 ELSE 0 END) as total_appels,
                  SUM(CASE WHEN type_relance = 'WHATSAPP' THEN 1 ELSE 0 END) as total_whatsapp,
                  SUM(CASE WHEN type_relance = 'SMS' THEN 1 ELSE 0 END) as total_sms,
                  SUM(CASE WHEN type_relance = 'EMAIL' THEN 1 ELSE 0 END) as total_emails,
                  SUM(CASE WHEN type_relance = 'VISITE' THEN 1 ELSE 0 END) as total_visites,
                  SUM(CASE WHEN resultat = 'REPONDU' THEN 1 ELSE 0 END) as repondu,
                  SUM(CASE WHEN resultat = 'PAS_REPONDU' THEN 1 ELSE 0 END) as pas_repondu,
                  SUM(CASE WHEN resultat = 'RDV_OBTENU' THEN 1 ELSE 0 END) as rdv_obtenu,
                  SUM(CASE WHEN resultat = 'A_RAPPELER' THEN 1 ELSE 0 END) as a_rappeler,
                  SUM(CASE WHEN resultat = 'REFUSE' THEN 1 ELSE 0 END) as refuse
                  FROM " . $this->table . " r
                  WHERE 1=1";
        
        if(!empty($filters['marketiste_id'])) {
            $query .= " AND r.utilisateur_id = :marketiste_id";
        }
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND DATE(r.date_relance) BETWEEN :date_debut AND :date_fin";
        }
        
        $stmt = $this->conn->prepare($query);
        
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
                  COUNT(r.id) as total_relances,
                  SUM(CASE WHEN r.type_relance = 'APPEL' THEN 1 ELSE 0 END) as appels,
                  SUM(CASE WHEN r.type_relance = 'WHATSAPP' THEN 1 ELSE 0 END) as whatsapp,
                  SUM(CASE WHEN r.type_relance = 'EMAIL' THEN 1 ELSE 0 END) as emails,
                  SUM(CASE WHEN r.resultat = 'RDV_OBTENU' THEN 1 ELSE 0 END) as rdv_obtenus
                  FROM " . $this->table . " r
                  JOIN utilisateurs u ON r.utilisateur_id = u.id
                  WHERE 1=1";
        
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query .= " AND DATE(r.date_relance) BETWEEN :date_debut AND :date_fin";
        }
        
        $query .= " GROUP BY r.utilisateur_id ORDER BY total_relances DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindParam(":date_debut", $filters['date_debut']);
            $stmt->bindParam(":date_fin", $filters['date_fin']);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Statistiques par type de relance sur une période
    public function getStatsByPeriod($periode = 'day') {
        $format = '';
        switch($periode) {
            case 'day':
                $format = '%Y-%m-%d';
                break;
            case 'week':
                $format = '%Y-%u';
                break;
            case 'month':
                $format = '%Y-%m';
                break;
            case 'year':
                $format = '%Y';
                break;
        }
        
        $query = "SELECT 
                  DATE_FORMAT(r.date_relance, '$format') as periode,
                  COUNT(*) as total,
                  SUM(CASE WHEN r.type_relance = 'APPEL' THEN 1 ELSE 0 END) as appels,
                  SUM(CASE WHEN r.type_relance = 'WHATSAPP' THEN 1 ELSE 0 END) as whatsapp,
                  SUM(CASE WHEN r.type_relance = 'EMAIL' THEN 1 ELSE 0 END) as emails,
                  SUM(CASE WHEN r.type_relance = 'SMS' THEN 1 ELSE 0 END) as sms,
                  SUM(CASE WHEN r.type_relance = 'VISITE' THEN 1 ELSE 0 END) as visites
                  FROM " . $this->table . " r
                  GROUP BY periode
                  ORDER BY periode DESC
                  LIMIT 12";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Lire une relance par ID
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
            $this->type_relance = $row['type_relance'];
            $this->resultat = $row['resultat'];
            $this->commentaire = $row['commentaire'];
            $this->date_relance = $row['date_relance'];
            return true;
        }
        return false;
    }
    
    // Mettre à jour une relance
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET type_relance = :type_relance,
                      resultat = :resultat,
                      commentaire = :commentaire
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->type_relance = htmlspecialchars(strip_tags($this->type_relance));
        $this->resultat = htmlspecialchars(strip_tags($this->resultat));
        $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
        
        $stmt->bindParam(":type_relance", $this->type_relance);
        $stmt->bindParam(":resultat", $this->resultat);
        $stmt->bindParam(":commentaire", $this->commentaire);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Supprimer une relance
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Dernières relances
    public function getLastRelances($limit = 10) {
        $query = "SELECT r.*, 
                  CONCAT(p.nom, ' ', p.prenom) as prospect_nom,
                  CONCAT(u.nom, ' ', u.prenom) as marketiste_nom
                  FROM " . $this->table . " r
                  LEFT JOIN prospects p ON r.prospect_id = p.id
                  LEFT JOIN utilisateurs u ON r.utilisateur_id = u.id
                  ORDER BY r.date_relance DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
?>