<?php
class Dashboard {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Statistiques des prospects
    public function getProspectsStats() {
        $query = "SELECT 
                  COUNT(*) as total,
                  SUM(CASE WHEN DATE(date_creation) = CURDATE() THEN 1 ELSE 0 END) as aujourd_hui,
                  SUM(CASE WHEN MONTH(date_creation) = MONTH(CURDATE()) 
                      AND YEAR(date_creation) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as ce_mois,
                  SUM(CASE WHEN statut = 'INSCRIT' THEN 1 ELSE 0 END) as inscrits
                  FROM prospects";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Statistiques des relances
    public function getRelancesStats() {
        $query = "SELECT COUNT(*) as total FROM relances";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Statistiques des rendez-vous
    public function getRendezVousStats() {
        $query = "SELECT 
                  COUNT(*) as total,
                  SUM(CASE WHEN date_rdv = CURDATE() AND statut IN ('PLANIFIE', 'CONFIRME') THEN 1 ELSE 0 END) as aujourd_hui
                  FROM rendez_vous";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Taux de conversion
    public function getConversionRate() {
        $query = "SELECT 
                  COUNT(*) as total_prospects,
                  SUM(CASE WHEN statut = 'INSCRIT' THEN 1 ELSE 0 END) as inscrits
                  FROM prospects";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $taux = $result['total_prospects'] > 0 
                ? round(($result['inscrits'] / $result['total_prospects']) * 100, 2) 
                : 0;
        
        return ['taux' => $taux, 'total' => $result['total_prospects'], 'inscrits' => $result['inscrits']];
    }
    
    // Nombre de départements
    public function getDepartementsCount() {
        $query = "SELECT COUNT(*) as total FROM departements";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Nombre de spécialités
    public function getSpecialitesCount() {
        $query = "SELECT COUNT(*) as total FROM specialites";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Nombre d'utilisateurs actifs
    public function getUtilisateursActifsCount() {
        $query = "SELECT COUNT(*) as total FROM utilisateurs WHERE statut = 'ACTIF'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Prospects par mois (12 derniers mois)
    public function getProspectsByMonth() {
        $query = "SELECT 
                  DATE_FORMAT(date_creation, '%Y-%m') as mois,
                  DATE_FORMAT(date_creation, '%b %Y') as mois_label,
                  COUNT(*) as total
                  FROM prospects
                  WHERE date_creation >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                  GROUP BY DATE_FORMAT(date_creation, '%Y-%m')
                  ORDER BY DATE_FORMAT(date_creation, '%Y-%m') ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Inscriptions par mois (12 derniers mois)
    public function getInscriptionsByMonth() {
        $query = "SELECT 
                  DATE_FORMAT(date_creation, '%Y-%m') as mois,
                  DATE_FORMAT(date_creation, '%b %Y') as mois_label,
                  COUNT(*) as total
                  FROM prospects
                  WHERE statut = 'INSCRIT' 
                  AND date_creation >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                  GROUP BY DATE_FORMAT(date_creation, '%Y-%m')
                  ORDER BY DATE_FORMAT(date_creation, '%Y-%m') ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Prospects par département
    public function getProspectsByDepartement() {
        $query = "SELECT 
                  d.nom as departement,
                  COUNT(p.id) as total
                  FROM departements d
                  LEFT JOIN specialites s ON d.id = s.departement_id
                  LEFT JOIN prospects p ON s.id = p.specialite_id
                  GROUP BY d.id, d.nom
                  ORDER BY total DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Prospects par source marketing
    public function getProspectsBySource() {
        $query = "SELECT 
                  sm.nom as source,
                  COUNT(p.id) as total
                  FROM sources_marketing sm
                  LEFT JOIN prospects p ON sm.id = p.source_id
                  GROUP BY sm.id, sm.nom
                  ORDER BY total DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Performance des marketistes
    public function getMarketistesPerformance() {
        $query = "SELECT 
                  CONCAT(u.nom, ' ', u.prenom) as marketiste,
                  COUNT(DISTINCT p.id) as total_prospects,
                  SUM(CASE WHEN p.statut = 'INSCRIT' THEN 1 ELSE 0 END) as inscriptions,
                  COUNT(r.id) as relances,
                  COUNT(rdv.id) as rendez_vous,
                  SUM(CASE WHEN rdv.statut = 'REALISE' THEN 1 ELSE 0 END) as rdv_realises
                  FROM utilisateurs u
                  LEFT JOIN prospects p ON u.id = p.marketiste_id
                  LEFT JOIN relances r ON u.id = r.utilisateur_id
                  LEFT JOIN rendez_vous rdv ON u.id = rdv.utilisateur_id
                  WHERE u.role = 'MARKETISTE'
                  GROUP BY u.id, u.nom, u.prenom
                  ORDER BY inscriptions DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Dernières activités
    public function getLastActivities($limit = 10) {
        $activities = [];
        
        // Derniers prospects
        $query1 = "SELECT 
                   'prospect' as type,
                   id,
                   CONCAT(nom, ' ', prenom) as nom,
                   date_creation as date,
                   statut
                   FROM prospects
                   ORDER BY date_creation DESC
                   LIMIT 5";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
        $prospects = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        
        // Derniers rendez-vous
        $query2 = "SELECT 
                   'rendez_vous' as type,
                   rdv.id,
                   CONCAT(p.nom, ' ', p.prenom) as nom,
                   rdv.date_rdv as date,
                   rdv.statut
                   FROM rendez_vous rdv
                   JOIN prospects p ON rdv.prospect_id = p.id
                   ORDER BY rdv.date_rdv DESC
                   LIMIT 5";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->execute();
        $rendezvous = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        // Fusionner les activités
        $activities = array_merge($prospects, $rendezvous);
        
        // Trier par date
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($activities, 0, $limit);
    }
}
?>