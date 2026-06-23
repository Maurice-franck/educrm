<?php
class Departement {
    private $conn;
    private $table = "departements";
    
    public $id;
    public $nom;
    public $description;
    public $date_creation;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer un département
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET nom = :nom, 
                      description = :description";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":description", $this->description);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lire tous les départements
    public function readAll() {
        $query = "SELECT d.*, 
                  (SELECT COUNT(*) FROM specialites WHERE departement_id = d.id) as total_specialites
                  FROM " . $this->table . " d 
                  ORDER BY d.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Lire un département par ID
    public function readOne() {
        $query = "SELECT d.*, 
                  (SELECT COUNT(*) FROM specialites WHERE departement_id = d.id) as total_specialites
                  FROM " . $this->table . " d 
                  WHERE d.id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nom = $row['nom'];
            $this->description = $row['description'];
            $this->date_creation = $row['date_creation'];
            return true;
        }
        return false;
    }
    
    // Mettre à jour un département
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET nom = :nom, 
                      description = :description
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Supprimer un département
    public function delete() {
        // Vérifier si le département a des spécialités
        $checkQuery = "SELECT COUNT(*) as total FROM specialites WHERE departement_id = :id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":id", $this->id);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if($result['total'] > 0) {
            return false;
        }
        
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Rechercher des départements
    public function search($keyword) {
        $query = "SELECT d.*, 
                  (SELECT COUNT(*) FROM specialites WHERE departement_id = d.id) as total_specialites
                  FROM " . $this->table . " d 
                  WHERE d.nom LIKE :keyword 
                  OR d.description LIKE :keyword
                  ORDER BY d.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        return $stmt;
    }
}
?>