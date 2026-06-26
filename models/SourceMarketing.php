<?php
class SourceMarketing {
    private $conn;
    private $table = "sources_marketing";
    
    public $id;
    public $nom;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer une source
    public function create() {
        $query = "INSERT INTO " . $this->table . " SET nom = :nom";
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $stmt->bindParam(":nom", $this->nom);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lire toutes les sources
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nom";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Lire une source par ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nom = $row['nom'];
            return true;
        }
        return false;
    }
    
    // Mettre à jour une source
    public function update() {
        $query = "UPDATE " . $this->table . " SET nom = :nom WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Supprimer une source
    public function delete() {
        // Vérifier si la source est utilisée dans des prospects
        $checkQuery = "SELECT COUNT(*) as total FROM prospects WHERE source_id = :id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":id", $this->id);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if($result['total'] > 0) {
            return false; // Ne peut pas supprimer car utilisée
        }
        
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Vérifier si le nom existe déjà
    public function nameExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE nom = :nom";
        if($this->id) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $stmt->bindParam(":nom", $this->nom);
        if($this->id) {
            $stmt->bindParam(":id", $this->id);
        }
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    // Compter le nombre de prospects par source
    public function countProspectsBySource() {
        $query = "SELECT s.*, COUNT(p.id) as total_prospects 
                  FROM " . $this->table . " s
                  LEFT JOIN prospects p ON s.id = p.source_id
                  GROUP BY s.id
                  ORDER BY total_prospects DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>