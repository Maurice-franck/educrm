<?php
// models/Setting.php
require_once __DIR__ . '/../config/database.php';

class Setting {

    private $db;

    public function __construct() {
    $database = new Database();
    $this->db = $database->getConnection();
}

    // Récupère tous les paramètres sous forme clé => valeur
    public function getAll() {
        $stmt = $this->db->query("SELECT cle, valeur FROM settings");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['cle']] = $row['valeur'];
        }
        return $result;
    }

    // Récupère un paramètre par sa clé
    public function get($cle, $default = '') {
        $stmt = $this->db->prepare("SELECT valeur FROM settings WHERE cle = ?");
        $stmt->execute([$cle]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? ($row['valeur'] ?? $default) : $default;
    }

    // Met à jour un ou plusieurs paramètres
    public function update($data) {
        $stmt = $this->db->prepare("
            INSERT INTO settings (cle, valeur) VALUES (:cle, :valeur)
            ON DUPLICATE KEY UPDATE valeur = :valeur
        ");
        foreach ($data as $cle => $valeur) {
            $stmt->execute(['cle' => $cle, 'valeur' => $valeur]);
        }
        return true;
    }
}
