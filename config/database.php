<?php
class Database {
    private $host = "localhost";
    private $db_name = "educrm";
    private $username = "root";
    private $password = "";
    public $conn;
    
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // Afficher une page d'erreur claire au lieu d'une page blanche
            http_response_code(500);
            echo "<!DOCTYPE html><html><head><title>Erreur de connexion</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head><body><div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4><strong>Erreur de connexion à la base de données</strong></h4>
                <p>" . htmlspecialchars($exception->getMessage()) . "</p>
                <hr>
                <p class='mb-0'>Vérifiez que MySQL est démarré et que la base <strong>educrm</strong> existe.</p>
            </div></div></body></html>";
            exit;
        }
        return $this->conn;
    }
}
?>