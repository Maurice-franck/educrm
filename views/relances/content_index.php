<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-chart-line me-2"></i>Supervision des Relances
    </h2>
    <div>
        <a href="/educrm/relances/report" class="btn btn-info me-2">
            <i class="fas fa-chart-bar me-2"></i>Rapport détaillé
        </a>
        <a href="/educrm/relances/all" class="btn btn-primary me-2">
            <i class="fas fa-list me-2"></i>Toutes les relances
        </a>
        <a href="/educrm/relances/create" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Nouvelle relance
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
        <form method="GET" action="/educrm/relances" class="row g-3">
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
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select class="form-select" name="type_relance">
                    <option value="">Tous</option>
                    <option value="APPEL" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'APPEL') ? 'selected' : ''; ?>>Appel</option>
                    <option value="WHATSAPP" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'WHATSAPP') ? 'selected' : ''; ?>>WhatsApp</option>
                    <option value="SMS" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'SMS') ? 'selected' : ''; ?>>SMS</option>
                    <option value="EMAIL" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'EMAIL') ? 'selected' : ''; ?>>Email</option>
                    <option value="VISITE" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'VISITE') ? 'selected' : ''; ?>>Visite</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Résultat</label>
                <select class="form-select" name="resultat">
                    <option value="">Tous</option>
                    <option value="REPONDU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'REPONDU') ? 'selected' : ''; ?>>Répondu</option>
                    <option value="PAS_REPONDU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'PAS_REPONDU') ? 'selected' : ''; ?>>Pas répondu</option>
                    <option value="RDV_OBTENU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'RDV_OBTENU') ? 'selected' : ''; ?>>RDV obtenu</option>
                    <option value="A_RAPPELER" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'A_RAPPELER') ? 'selected' : ''; ?>>À rappeler</option>
                    <option value="REFUSE" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'REFUSE') ? 'selected' : ''; ?>>Refusé</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques globales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total relances</h5>
                <h2><?php echo $stats['total']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">RDV obtenus</h5>
                <h2><?php echo $stats['rdv_obtenu']; ?></h2>
                <small>Taux: <?php echo $stats['total'] > 0 ? round(($stats['rdv_obtenu'] / $stats['total']) * 100, 1) : 0; ?>%</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Taux de réponse</h5>
                <h2><?php echo $stats['total'] > 0 ? round((($stats['repondu'] + $stats['rdv_obtenu']) / $stats['total']) * 100, 1) : 0; ?>%</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">À rappeler</h5>
                <h2><?php echo $stats['a_rappeler']; ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques par type de relance -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Répartition par type
                </h5>
            </div>
            <div class="card-body">
                <canvas id="typeChart" height="250"></canvas>
                <div class="row mt-3 text-center">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <i class="fas fa-phone text-primary"></i>
                            <h5><?php echo $stats['total_appels']; ?></h5>
                            <small>Appels</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <i class="fab fa-whatsapp text-success"></i>
                            <h5><?php echo $stats['total_whatsapp']; ?></h5>
                            <small>WhatsApp</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <i class="fas fa-envelope text-info"></i>
                            <h5><?php echo $stats['total_emails']; ?></h5>
                            <small>Emails</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Évolution mensuelle
                </h5>
            </div>
            <div class="card-body">
                <canvas id="evolutionChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Performance par marketiste -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Performance par marketiste
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Marketiste</th>
                        <th>Total relances</th>
                        <th>Appels</th>
                        <th>WhatsApp</th>
                        <th>Emails</th>
                        <th>RDV obtenus</th>
                        <th>Taux de conversion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $statsByMarketiste->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['marketiste_nom']); ?></td>
                            <td><?php echo $row['total_relances']; ?></td>
                            <td><?php echo $row['appels']; ?></td>
                            <td><?php echo $row['whatsapp']; ?></td>
                            <td><?php echo $row['emails']; ?></td>
                            <td>
                                <span class="badge bg-success"><?php echo $row['rdv_obtenus']; ?></span>
                            </td>
                            <td>
                                <?php 
                                $taux = $row['total_relances'] > 0 ? round(($row['rdv_obtenus'] / $row['total_relances']) * 100, 1) : 0;
                                ?>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: <?php echo $taux; ?>%">
                                        <?php echo $taux; ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Dernières relances -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-clock me-2"></i>Dernières relances
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Prospect</th>
                        <th>Marketiste</th>
                        <th>Type</th>
                        <th>Résultat</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($relance = $lastRelances->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($relance['date_relance'])); ?></td>
                            <td><?php echo htmlspecialchars($relance['prospect_nom']); ?></td>
                            <td><?php echo htmlspecialchars($relance['marketiste_nom']); ?></td>
                            <td>
                                <?php
                                $icon = '';
                                switch($relance['type_relance']) {
                                    case 'APPEL': $icon = 'fa-phone'; break;
                                    case 'WHATSAPP': $icon = 'fa-whatsapp'; break;
                                    case 'EMAIL': $icon = 'fa-envelope'; break;
                                    case 'SMS': $icon = 'fa-sms'; break;
                                    case 'VISITE': $icon = 'fa-building'; break;
                                }
                                ?>
                                <i class="fab <?php echo $icon; ?>"></i> <?php echo $relance['type_relance']; ?>
                            </td>
                            <td>
                                <?php
                                $badgeClass = '';
                                switch($relance['resultat']) {
                                    case 'REPONDU': $badgeClass = 'bg-info'; break;
                                    case 'PAS_REPONDU': $badgeClass = 'bg-secondary'; break;
                                    case 'RDV_OBTENU': $badgeClass = 'bg-success'; break;
                                    case 'A_RAPPELER': $badgeClass = 'bg-warning'; break;
                                    case 'REFUSE': $badgeClass = 'bg-danger'; break;
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo str_replace('_', ' ', $relance['resultat']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(substr($relance['commentaire'], 0, 50)) . (strlen($relance['commentaire']) > 50 ? '...' : ''); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des types de relance
const ctx1 = document.getElementById('typeChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Appels', 'WhatsApp', 'SMS', 'Emails', 'Visites'],
        datasets: [{
            data: [<?php echo $stats['total_appels']; ?>, <?php echo $stats['total_whatsapp']; ?>, 
                    <?php echo $stats['total_sms']; ?>, <?php echo $stats['total_emails']; ?>, 
                    <?php echo $stats['total_visites']; ?>],
            backgroundColor: ['#007bff', '#25D366', '#6c757d', '#17a2b8', '#ffc107']
        }]
    }
});

// Graphique d'évolution
const ctx2 = document.getElementById('evolutionChart').getContext('2d');
const evolutionData = <?php 
    $data = [];
    $periodStats = $this->relance->getStatsByPeriod('month');
    while($row = $periodStats->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
?>;

new Chart(ctx2, {
    type: 'line',
    data: {
        labels: evolutionData.map(d => d.periode),
        datasets: [
            {
                label: 'Appels',
                data: evolutionData.map(d => d.appels),
                borderColor: '#007bff',
                fill: false
            },
            {
                label: 'WhatsApp',
                data: evolutionData.map(d => d.whatsapp),
                borderColor: '#25D366',
                fill: false
            },
            {
                label: 'Emails',
                data: evolutionData.map(d => d.emails),
                borderColor: '#17a2b8',
                fill: false
            }
        ]
    }
});
</script>