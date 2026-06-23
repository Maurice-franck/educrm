<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-chart-bar me-2"></i>Rapport détaillé des relances
    </h2>
    <a href="/educrm/relances" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<!-- Filtres période -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/educrm/relances/report" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Date début</label>
                <input type="date" class="form-control" name="date_debut" 
                       value="<?php echo isset($_GET['date_debut']) ? $_GET['date_debut'] : date('Y-m-01'); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date fin</label>
                <input type="date" class="form-control" name="date_fin" 
                       value="<?php echo isset($_GET['date_fin']) ? $_GET['date_fin'] : date('Y-m-t'); ?>">
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
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-chart-line me-2"></i>Générer le rapport
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3><?php echo $stats['total']; ?></h3>
                <p class="mb-0">Total relances</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3><?php echo $stats['rdv_obtenu']; ?></h3>
                <p class="mb-0">RDV obtenus</p>
                <small>Taux: <?php echo $stats['total'] > 0 ? round(($stats['rdv_obtenu'] / $stats['total']) * 100, 1) : 0; ?>%</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3><?php echo $stats['total'] > 0 ? round((($stats['repondu'] + $stats['rdv_obtenu']) / $stats['total']) * 100, 1) : 0; ?>%</h3>
                <p class="mb-0">Taux de réponse</p>
            </div>
        </div>
    </div>
</div>

<!-- Résumé des types de relance -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Résumé par type de relance
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-phone fa-2x text-primary mb-2"></i>
                            <h4><?php echo $stats['total_appels']; ?></h4>
                            <p class="mb-0">Appels</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fab fa-whatsapp fa-2x text-success mb-2"></i>
                            <h4><?php echo $stats['total_whatsapp']; ?></h4>
                            <p class="mb-0">WhatsApp</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-envelope fa-2x text-info mb-2"></i>
                            <h4><?php echo $stats['total_emails']; ?></h4>
                            <p class="mb-0">Emails</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-sms fa-2x text-secondary mb-2"></i>
                            <h4><?php echo $stats['total_sms']; ?></h4>
                            <p class="mb-0">SMS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance par marketiste -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-trophy me-2"></i>Performance par marketiste
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="table-dark">
                        <th>Marketiste</th>
                        <th>Relances</th>
                        <th>Appels</th>
                        <th>WhatsApp</th>
                        <th>Emails</th>
                        <th>RDV obtenus</th>
                        <th>Taux conversion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    while($row = $statsByMarketiste->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                         <tr>
                            <td>
                                <?php if($rank == 1): ?>
                                    <i class="fas fa-crown text-warning"></i>
                                <?php elseif($rank == 2): ?>
                                    <i class="fas fa-medal text-secondary"></i>
                                <?php elseif($rank == 3): ?>
                                    <i class="fas fa-medal text-bronze"></i>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($row['marketiste_nom']); ?>
                            </td>
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
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo $taux; ?>%">
                                        <?php echo $taux; ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php 
                    $rank++;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.text-bronze {
    color: #cd7f32;
}
</style>