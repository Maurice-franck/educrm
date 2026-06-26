<?php
class Utilisateur {
    private $conn;
    private $table = "utilisateurs";
    
    public $id;
    public $nom;
    public $prenom;
    public $telephone;
    public $email;
    public $mot_de_passe;
    public $role;
    public $statut;
    public $departement_id;
    public $departement_nom;
    public $date_creation;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer un utilisateur
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET nom = :nom, 
                      prenom = :prenom, 
                      telephone = :telephone, 
                      email = :email, 
                      mot_de_passe = :mot_de_passe, 
                      role = :role, 
                      departement_id = :departement_id,
                      statut = 'ACTIF'";
        
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
        $this->role = htmlspecialchars(strip_tags($this->role));
        // Le département n'a de sens que pour un CHEF_DEPARTEMENT
        $this->departement_id = ($this->role === 'CHEF_DEPARTEMENT' && !empty($this->departement_id))
            ? $this->departement_id
            : null;
        
        // Lier les valeurs
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mot_de_passe", $this->mot_de_passe);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":departement_id", $this->departement_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lire tous les utilisateurs
    public function readAll() {
        $query = "SELECT u.*, d.nom as departement_nom 
                  FROM " . $this->table . " u
                  LEFT JOIN departements d ON u.departement_id = d.id
                  ORDER BY u.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Lire un utilisateur par ID
    public function readOne() {
        $query = "SELECT u.*, d.nom as departement_nom 
                  FROM " . $this->table . " u
                  LEFT JOIN departements d ON u.departement_id = d.id
                  WHERE u.id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->telephone = $row['telephone'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->statut = $row['statut'];
            $this->departement_id = $row['departement_id'];
            $this->departement_nom = $row['departement_nom'];
            $this->date_creation = $row['date_creation'];
            return true;
        }
        return false;
    }
    
    // Mettre à jour un utilisateur
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET nom = :nom, 
                      prenom = :prenom, 
                      telephone = :telephone, 
                      email = :email, 
                      role = :role,
                      departement_id = :departement_id
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        // Le département n'a de sens que pour un CHEF_DEPARTEMENT
        $this->departement_id = ($this->role === 'CHEF_DEPARTEMENT' && !empty($this->departement_id))
            ? $this->departement_id
            : null;
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":departement_id", $this->departement_id);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Réinitialiser le mot de passe
    public function resetPassword($new_password) {
        $query = "UPDATE " . $this->table . " 
                  SET mot_de_passe = :mot_de_passe
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":mot_de_passe", $hashed_password);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Désactiver un utilisateur
    public function deactivate() {
        $query = "UPDATE " . $this->table . " SET statut = 'INACTIF' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Réactiver un utilisateur
    public function activate() {
        $query = "UPDATE " . $this->table . " SET statut = 'ACTIF' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Vérifier si l'email existe déjà
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

// ============================================================
// AJOUTE CES 4 MÉTHODES dans models/Utilisateur.php
// juste avant la dernière accolade } de la classe
// ============================================================

    public function getById($id) {
    $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getByEmail($email) {
    $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateProfil($id, $data) {
    $stmt = $this->conn->prepare("
        UPDATE utilisateurs SET
            nom       = :nom,
            prenom    = :prenom,
            telephone = :telephone,
            email     = :email
        WHERE id = :id
    ");
    $data['id'] = $id;
    return $stmt->execute($data);
}

public function updateMotDePasse($id, $hash) {
    $stmt = $this->conn->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
    return $stmt->execute([$hash, $id]);
}

   
}
?>