<?php
/**
 * Modèle Auth - Gère l'authentification des utilisateurs
 * Architecture MVC - Programmation Orientée Objet
 */
class Auth {
    private $conn;
    private $table = "utilisateurs";

    // Propriétés de l'utilisateur connecté
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $role;
    public $statut;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Authentifie un utilisateur par email et mot de passe
     * Retourne true si succès, false sinon
     */
    public function login(string $email, string $password): bool {
        $email = htmlspecialchars(strip_tags($email));

        $query = "SELECT id, nom, prenom, email, mot_de_passe, role, statut 
                  FROM " . $this->table . " 
                  WHERE email = :email AND statut = 'ACTIF' 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe (supporte password_hash ET plain text legacy)
        $passwordValid = false;
        if (password_verify($password, $row['mot_de_passe'])) {
            $passwordValid = true;
        } elseif ($password === $row['mot_de_passe']) {
            // Compatibilité avec les anciens comptes non hashés (à migrer)
            $passwordValid = true;
        }

        if (!$passwordValid) {
            return false;
        }

        // Hydratation de l'objet
        $this->id     = $row['id'];
        $this->nom    = $row['nom'];
        $this->prenom = $row['prenom'];
        $this->email  = $row['email'];
        $this->role   = $row['role'];
        $this->statut = $row['statut'];

        return true;
    }

    /**
     * Enregistre l'utilisateur en session
     */
    public function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id']     = $this->id;
        $_SESSION['user_nom']    = $this->nom;
        $_SESSION['user_prenom'] = $this->prenom;
        $_SESSION['user_email']  = $this->email;
        $_SESSION['user_role']   = $this->role;
        $_SESSION['logged_in']   = true;
    }

    /**
     * Déconnecte l'utilisateur et détruit la session
     */
    public static function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Vérifie si un utilisateur est connecté
     */
    public static function isLoggedIn(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Retourne la redirection selon le rôle
     */
    public static function getRedirectByRole(string $role): string {
        switch ($role) {
            case 'ADMIN':
                return '/educrm/dashboard';
            case 'CHEF_DEPARTEMENT':
                return '/educrm/prospects';
            case 'MARKETISTE':
                return '/educrm/relances';
            default:
                return '/educrm/';
        }
    }

    /**
     * Vérifie que l'utilisateur a le rôle requis, sinon redirige
     */
    public static function requireRole(array $roles): void {
        if (!self::isLoggedIn()) {
            header('Location: /educrm/login');
            exit;
        }
        if (!in_array($_SESSION['user_role'], $roles)) {
            header('Location: /educrm/access-denied');
            exit;
        }
    }
}
?>
