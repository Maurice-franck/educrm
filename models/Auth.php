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
    public $departement_id;
    public $departement_nom;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Authentifie un utilisateur par email et mot de passe
     * Retourne true si succès, false sinon
     */
    public function login(string $email, string $password): bool {
        $email = htmlspecialchars(strip_tags($email));

        $query = "SELECT u.id, u.nom, u.prenom, u.email, u.mot_de_passe, u.role, u.statut, u.departement_id,
                  d.nom as departement_nom
                  FROM " . $this->table . " u
                  LEFT JOIN departements d ON u.departement_id = d.id
                  WHERE u.email = :email AND u.statut = 'ACTIF' 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

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
        $this->departement_id   = $row['departement_id'];
        $this->departement_nom  = $row['departement_nom'];

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
        $_SESSION['departement_id']  = $this->departement_id;
        $_SESSION['departement_nom'] = $this->departement_nom;
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
                return '/educrm/chef-departement/supervision';
            case 'MARKETISTE':
                return '/educrm/marketiste/supervision';
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
