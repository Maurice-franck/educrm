<?php
require_once 'models/Dashboard.php';

class DashboardController {
    private $db;
    private $dashboard;
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->dashboard = new Dashboard($this->db);
    }
    
    private function getConnection() {
        require_once 'config/database.php';
        $database = new Database();
        return $database->getConnection();
    }
    
    // Afficher le tableau de bord
    public function index() {
        // Indicateurs principaux
        $prospectsStats = $this->dashboard->getProspectsStats();
        $relancesStats = $this->dashboard->getRelancesStats();
        $rendezVousStats = $this->dashboard->getRendezVousStats();
        $conversionRate = $this->dashboard->getConversionRate();
        $departementsCount = $this->dashboard->getDepartementsCount();
        $specialitesCount = $this->dashboard->getSpecialitesCount();
        $utilisateursActifs = $this->dashboard->getUtilisateursActifsCount();
        
        // Données pour graphiques
        $prospectsByMonth = $this->dashboard->getProspectsByMonth();
        $inscriptionsByMonth = $this->dashboard->getInscriptionsByMonth();
        $prospectsByDepartement = $this->dashboard->getProspectsByDepartement();
        $prospectsBySource = $this->dashboard->getProspectsBySource();
        $marketistesPerformance = $this->dashboard->getMarketistesPerformance();
        $lastActivities = $this->dashboard->getLastActivities();
        
        require_once 'views/dashboard/index.php';
    }
}
?>