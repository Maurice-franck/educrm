<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-calendar-alt me-2"></i>Supervision des Rendez-vous
    </h2>
    <div>
        <a href="/educrm/rendezvous/calendar" class="btn btn-info me-2">
            <i class="fas fa-calendar-week me-2"></i>Calendrier
        </a>
        <a href="/educrm/rendezvous/all" class="btn btn-primary me-2">
            <i class="fas fa-list me-2"></i>Tous les rendez-vous
        </a>
        <a href="/educrm/rendezvous/create" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Nouveau rendez-vous
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>Filtres
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="/educrm/rendezvous" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Département</label>
                <select class="form-select" name="departement_id">
                    <option value="">Tous</option>
                    <?php foreach($departements as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>"
                            <?php echo (isset($_GET['departement_id']) && $_GET['departement_id'] == $dept['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Marketiste</label>
                <select class="form-select" name="marketiste_id">
                    <option value="">Tous</option>
                    <?php foreach($marketistes as $mkt): ?>
                        <?php if($mkt['role'] == 'MARKETISTE' || $mkt['role'] == 'ADMIN'): ?>
                            <option value="<?php echo $mkt['id']; ?>"
                                <?php echo (isset($_GET['marketiste_id']) && $_GET['marketiste_id'] == $mkt['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mkt['nom'] . ' ' . $mkt['prenom']); ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date début</label>
                <input type="date" class="form-control" name="date_debut" 
                       value="<?php echo isset($_GET['date_debut']) ? $_GET['date_debut'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date fin</label>
                <input type="date" class="form-control" name="date_fin" 
                       value="<?php echo isset($_GET['date_fin']) ? $_GET['date_fin'] : ''; ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total rendez-vous</h5>
                <h2><?php echo $stats['total']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">À venir</h5>
                <h2><?php echo $stats['a_venir']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Réalisés</h5>
                <h2><?php echo $stats['realise']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Annulés</h5>
                <h2><?php echo $stats['annule']; ?></h2>
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
                    <i class="fas fa-chart-pie me-2"></i>Répartition par statut
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statutChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Performance par marketiste
                </h5>
            </div>
            <div class="card-body">
                <canvas id="marketisteChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Rendez-vous du jour -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-calendar-day me-2"></i>Rendez-vous du jour
            <span class="badge bg-primary ms-2"><?php echo date('d/m/Y'); ?></span>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="table-dark">
                        <th>Heure</th>
                        <th>Prospect</th>
                        <th>Marketiste</th>
                        <th>Lieu</th>
                        <th>Objet</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($todayRdv->rowCount() > 0): ?>
                        <?php while($rdv = $todayRdv->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><strong><?php echo date('H:i', strtotime($rdv['heure_rdv'])); ?></strong></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($rdv['prospect_nom']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($rdv['prospect_telephone']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($rdv['marketiste_nom']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['lieu']); ?></td>
                                <td><?php echo htmlspecialchars($rdv['objet']); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = '';
                                    switch($rdv['statut']) {
                                        case 'PLANIFIE': $badgeClass = 'bg-warning'; break;
                                        case 'CONFIRME': $badgeClass = 'bg-info'; break;
                                        case 'REALISE': $badgeClass = 'bg-success'; break;
                                        case 'ANNULE': $badgeClass = 'bg-danger'; break;
                                        case 'REPORTE': $badgeClass = 'bg-secondary'; break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo $rdv['statut']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/educrm/rendezvous/<?php echo $rdv['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#statutModal<?php echo $rdv['id']; ?>"
                                                title="Changer statut">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal Changer statut -->
                                    <div class="modal fade" id="statutModal<?php echo $rdv['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="/educrm/rendezvous/<?php echo $rdv['id']; ?>/change-statut" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Changer le statut</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <select class="form-select" name="statut" required>
                                                            <option value="PLANIFIE" <?php echo $rdv['statut'] == 'PLANIFIE' ? 'selected' : ''; ?>>Planifié</option>
                                                            <option value="CONFIRME" <?php echo $rdv['statut'] == 'CONFIRME' ? 'selected' : ''; ?>>Confirmé</option>
                                                            <option value="REALISE" <?php echo $rdv['statut'] == 'REALISE' ? 'selected' : ''; ?>>Réalisé</option>
                                                            <option value="ANNULE" <?php echo $rdv['statut'] == 'ANNULE' ? 'selected' : ''; ?>>Annulé</option>
                                                            <option value="REPORTE" <?php echo $rdv['statut'] == 'REPORTE' ? 'selected' : ''; ?>>Reporté</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucun rendez-vous programmé pour aujourd'hui
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Prochains rendez-vous -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-clock me-2"></i>Prochains rendez-vous
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="table-dark">
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Prospect</th>
                        <th>Marketiste</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($rdv = $upcomingRdv->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($rdv['date_rdv'])); ?></td>
                            <td><?php echo date('H:i', strtotime($rdv['heure_rdv'])); ?></td>
                            <td><?php echo htmlspecialchars($rdv['prospect_nom']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['marketiste_nom']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['lieu']); ?></td>
                            <td>
                                <?php
                                $badgeClass = '';
                                switch($rdv['statut']) {
                                    case 'PLANIFIE': $badgeClass = 'bg-warning'; break;
                                    case 'CONFIRME': $badgeClass = 'bg-info'; break;
                                    case 'REALISE': $badgeClass = 'bg-success'; break;
                                    case 'ANNULE': $badgeClass = 'bg-danger'; break;
                                    case 'REPORTE': $badgeClass = 'bg-secondary'; break;
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo $rdv['statut']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/educrm/rendezvous/<?php echo $rdv['id']; ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des statuts
const ctx1 = document.getElementById('statutChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Planifié', 'Confirmé', 'Réalisé', 'Annulé', 'Reporté'],
        datasets: [{
            data: [<?php echo $stats['planifie']; ?>, <?php echo $stats['confirme']; ?>, 
                    <?php echo $stats['realise']; ?>, <?php echo $stats['annule']; ?>, 
                    <?php echo $stats['reporte']; ?>],
            backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545', '#6c757d']
        }]
    }
});

// Graphique des marketistes
const marketisteData = <?php 
    $data = [];
    $statsByMarketiste->execute();
    while($row = $statsByMarketiste->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
?>;

const ctx2 = document.getElementById('marketisteChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: marketisteData.map(d => d.marketiste_nom),
        datasets: [
            {
                label: 'Total RDV',
                data: marketisteData.map(d => d.total_rdv),
                backgroundColor: '#007bff'
            },
            {
                label: 'Réalisés',
                data: marketisteData.map(d => d.realise),
                backgroundColor: '#28a745'
            }
        ]
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
</script>