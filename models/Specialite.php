<?php
class Specialite {
    private $conn;
    private $table = "specialites";
    
    public $id;
    public $departement_id;
    public $nom;
    public $description;
    public $departement_nom;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer une spécialité
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET departement_id = :departement_id, 
                      nom = :nom, 
                      description = :description";
        
        $stmt = $this->conn->prepare($query);
        
        $this->departement_id = htmlspecialchars(strip_tags($this->departement_id));
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(":departement_id", $this->departement_id);
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":description", $this->description);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lire toutes les spécialités
    public function readAll() {
        $query = "SELECT s.*, d.nom as departement_nom 
                  FROM " . $this->table . " s
                  JOIN departements d ON s.departement_id = d.id
                  ORDER BY d.nom, s.nom";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Lire les spécialités par département
    public function readByDepartement($departement_id) {
        $query = "SELECT s.*, d.nom as departement_nom 
                  FROM " . $this->table . " s
                  JOIN departements d ON s.departement_id = d.id
                  WHERE s.departement_id = :departement_id
                  ORDER BY s.nom";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":departement_id", $departement_id);
        $stmt->execute();
        return $stmt;
    }
    
    // Lire une spécialité par ID
    public function readOne() {
        $query = "SELECT s.*, d.nom as departement_nom 
                  FROM " . $this->table . " s
                  JOIN departements d ON s.departement_id = d.id
                  WHERE s.id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->departement_id = $row['departement_id'];
            $this->nom = $row['nom'];
            $this->description = $row['description'];
            $this->departement_nom = $row['departement_nom'];
            return true;
        }
        return false;
    }
    
    // Mettre à jour une spécialité
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET departement_id = :departement_id, 
                      nom = :nom, 
                      description = :description
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->departement_id = htmlspecialchars(strip_tags($this->departement_id));
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(":departement_id", $this->departement_id);
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Supprimer une spécialité
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Rechercher des spécialités
    public function search($keyword) {
        $query = "SELECT s.*, d.nom as departement_nom 
                  FROM " . $this->table . " s
                  JOIN departements d ON s.departement_id = d.id
                  WHERE s.nom LIKE :keyword 
                  OR s.description LIKE :keyword
                  OR d.nom LIKE :keyword
                  ORDER BY d.nom, s.nom";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        return $stmt;
    }
    
    // Vérifier si le nom existe déjà dans le même département
    public function nameExistsInDepartement() {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE nom = :nom AND departement_id = :departement_id";
        if($this->id) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":departement_id", $this->departement_id);
        if($this->id) {
            $stmt->bindParam(":id", $this->id);
        }
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}
?>