<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-chart-line me-2"></i>Supervision du département
    </h2>
    <div>
        <a href="/educrm/chef-departement/supervision/all" class="btn btn-primary me-2">
            <i class="fas fa-list me-2"></i>Toutes les relances
        </a>
        <a href="/educrm/chef-departement/supervision/create" class="btn btn-success">
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
        <form method="GET" action="/educrm/chef-departement/supervision" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Marketiste</label>
                <select class="form-select" name="marketiste_id">
                    <option value="">Tous</option>
                    <?php foreach ($marketistes as $m): ?>
                        <option value="<?php echo $m['id']; ?>"
                            <?php echo (isset($_GET['marketiste_id']) && $_GET['marketiste_id'] == $m['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($m['nom'] . ' ' . $m['prenom']); ?>
                        </option>
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

<!-- Statistiques du département -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Relances du département</h5>
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

<!-- Répartition par type -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Répartition des relances par type
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2">
                        <div class="border rounded p-2">
                            <i class="fas fa-phone text-primary"></i>
                            <h5><?php echo $stats['total_appels']; ?></h5>
                            <small>Appels</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-2">
                            <i class="fab fa-whatsapp text-success"></i>
                            <h5><?php echo $stats['total_whatsapp']; ?></h5>
                            <small>WhatsApp</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-2">
                            <i class="fas fa-sms text-secondary"></i>
                            <h5><?php echo $stats['total_sms']; ?></h5>
                            <small>SMS</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-2">
                            <i class="fas fa-envelope text-info"></i>
                            <h5><?php echo $stats['total_emails']; ?></h5>
                            <small>Emails</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-2">
                            <i class="fas fa-building text-warning"></i>
                            <h5><?php echo $stats['total_visites']; ?></h5>
                            <small>Visites</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dernières relances du département -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-clock me-2"></i>Dernières relances du département
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
                    <?php if (count($lastRelances) > 0): ?>
                        <?php foreach ($lastRelances as $relance): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($relance['date_relance'])); ?></td>
                                <td><?php echo htmlspecialchars($relance['prospect_nom'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($relance['marketiste_nom'] ?? ''); ?></td>
                                <td>
                                    <?php
                                    $icon = '';
                                    switch ($relance['type_relance']) {
                                        case 'APPEL': $icon = 'fa-phone'; break;
                                        case 'WHATSAPP': $icon = 'fa-whatsapp'; break;
                                        case 'EMAIL': $icon = 'fa-envelope'; break;
                                        case 'SMS': $icon = 'fa-sms'; break;
                                        case 'VISITE': $icon = 'fa-building'; break;
                                    }
                                    ?>
                                    <i class="fas <?php echo $icon; ?>"></i> <?php echo $relance['type_relance']; ?>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = '';
                                    switch ($relance['resultat']) {
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
                                <td><?php echo htmlspecialchars(substr($relance['commentaire'] ?? '', 0, 50)) . (strlen($relance['commentaire'] ?? '') > 50 ? '...' : ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucune relance enregistrée dans le département
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
