<div class="mb-4">
    <h2>
        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord Administrateur
    </h2>
    <p class="text-muted">Bienvenue sur votre tableau de bord, voici un aperçu global de l'établissement.</p>
</div>

<!-- Cartes des indicateurs principaux -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Prospects</h6>
                        <h2 class="mb-0"><?php echo number_format($prospectsStats['total']); ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Prospects Aujourd'hui</h6>
                        <h2 class="mb-0"><?php echo number_format($prospectsStats['aujourd_hui']); ?></h2>
                    </div>
                    <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Prospects ce mois</h6>
                        <h2 class="mb-0"><?php echo number_format($prospectsStats['ce_mois']); ?></h2>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Taux de conversion</h6>
                        <h2 class="mb-0"><?php echo $conversionRate['taux']; ?>%</h2>
                        <small><?php echo $conversionRate['inscrits']; ?> / <?php echo $conversionRate['total']; ?> inscrits</small>
                    </div>
                    <i class="fas fa-percent fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Relances</h6>
                        <h2 class="mb-0"><?php echo number_format($relancesStats['total']); ?></h2>
                    </div>
                    <i class="fas fa-phone-alt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Rendez-vous programmés</h6>
                        <h2 class="mb-0"><?php echo number_format($rendezVousStats['total']); ?></h2>
                    </div>
                    <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Rendez-vous aujourd'hui</h6>
                        <h2 class="mb-0"><?php echo number_format($rendezVousStats['aujourd_hui']); ?></h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Inscriptions</h6>
                        <h2 class="mb-0"><?php echo number_format($prospectsStats['inscrits']); ?></h2>
                    </div>
                    <i class="fas fa-graduation-cap fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Départements</h6>
                        <h2 class="mb-0"><?php echo number_format($departementsCount['total']); ?></h2>
                    </div>
                    <i class="fas fa-building fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Spécialités</h6>
                        <h2 class="mb-0"><?php echo number_format($specialitesCount['total']); ?></h2>
                    </div>
                    <i class="fas fa-tags fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Utilisateurs actifs</h6>
                        <h2 class="mb-0"><?php echo number_format($utilisateursActifs['total']); ?></h2>
                    </div>
                    <i class="fas fa-user-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Évolution des prospects (12 mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="prospectsChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Évolution des inscriptions (12 mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="inscriptionsChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Prospects par département
                </h5>
            </div>
            <div class="card-body">
                <canvas id="departementChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Prospects par source marketing
                </h5>
            </div>
            <div class="card-body">
                <canvas id="sourceChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Performance des marketistes -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-trophy me-2"></i>Performance des marketistes
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Marketiste</th>
                        <th>Prospects</th>
                        <th>Inscriptions</th>
                        <th>Taux conversion</th>
                        <th>Relances</th>
                        <th>Rendez-vous</th>
                        <th>RDV réalisés</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($marketistesPerformance) > 0): ?>
                        <?php foreach($marketistesPerformance as $mkt): ?>
                            <?php 
                            $taux = $mkt['total_prospects'] > 0 
                                    ? round(($mkt['inscriptions'] / $mkt['total_prospects']) * 100, 1) 
                                    : 0;
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($mkt['marketiste']); ?></strong>
                                    <?php if($taux > 20): ?>
                                        <i class="fas fa-crown text-warning"></i>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($mkt['total_prospects']); ?></td>
                                <td>
                                    <span class="badge bg-success"><?php echo number_format($mkt['inscriptions']); ?></span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: <?php echo $taux; ?>%">
                                            <?php echo $taux; ?>%
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo number_format($mkt['relances']); ?></td>
                                <td><?php echo number_format($mkt['rendez_vous']); ?></td>
                                <td><?php echo number_format($mkt['rdv_realises']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucune donnée disponible</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Dernières activités -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i>Dernières activités
        </h5>
    </div>
    <div class="card-body">
        <div class="list-group">
            <?php foreach($lastActivities as $activity): ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">
                            <?php if($activity['type'] == 'prospect'): ?>
                                <i class="fas fa-user-plus text-primary me-2"></i>
                                Nouveau prospect : <?php echo htmlspecialchars($activity['nom']); ?>
                            <?php else: ?>
                                <i class="fas fa-calendar-check text-success me-2"></i>
                                Rendez-vous programmé : <?php echo htmlspecialchars($activity['nom']); ?>
                            <?php endif; ?>
                        </h6>
                        <small><?php echo date('d/m/Y H:i', strtotime($activity['date'])); ?></small>
                    </div>
                    <p class="mb-1">
                        Statut : 
                        <span class="badge bg-secondary">
                            <?php echo isset($activity['statut']) ? $activity['statut'] : '-'; ?>
                        </span>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique prospects par mois
const prospectsData = <?php echo json_encode($prospectsByMonth); ?>;
const ctx1 = document.getElementById('prospectsChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: prospectsData.map(d => d.mois_label),
        datasets: [{
            label: 'Nombre de prospects',
            data: prospectsData.map(d => d.total),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Graphique inscriptions par mois
const inscriptionsData = <?php echo json_encode($inscriptionsByMonth); ?>;
const ctx2 = document.getElementById('inscriptionsChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: inscriptionsData.map(d => d.mois_label),
        datasets: [{
            label: 'Nombre d\'inscriptions',
            data: inscriptionsData.map(d => d.total),
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Graphique prospects par département
const departementData = <?php echo json_encode($prospectsByDepartement); ?>;
const ctx3 = document.getElementById('departementChart').getContext('2d');
new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: departementData.map(d => d.departement),
        datasets: [{
            data: departementData.map(d => d.total),
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#fd7e14']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique prospects par source
const sourceData = <?php echo json_encode($prospectsBySource); ?>;
const ctx4 = document.getElementById('sourceChart').getContext('2d');
new Chart(ctx4, {
    type: 'pie',
    data: {
        labels: sourceData.map(d => d.source),
        datasets: [{
            data: sourceData.map(d => d.total),
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#fd7e14', '#20c997', '#e83e8c']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<style>
.opacity-50 {
    opacity: 0.5;
}
.card {
    transition: transform 0.2s;
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.card:hover {
    transform: translateY(-5px);
}
.progress {
    border-radius: 10px;
}
.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>