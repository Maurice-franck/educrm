<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-list me-2"></i>Tous les rendez-vous
    </h2>
    <div>
        <a href="/educrm/rendezvous/export<?php echo !empty($_GET) ? '?' . http_build_query($_GET) : ''; ?>" 
           class="btn btn-success me-2">
            <i class="fas fa-download me-2"></i>Exporter
        </a>
        <a href="/educrm/rendezvous" class="btn btn-secondary">
            <i class="fas fa-chart-line me-2"></i>Dashboard
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/educrm/rendezvous/all" class="row g-3">
            <div class="col-md-2">
                <input type="text" class="form-control" name="search" 
                       placeholder="Rechercher..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="departement_id">
                    <option value="">Département</option>
                    <?php foreach($departements as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>"
                            <?php echo (isset($_GET['departement_id']) && $_GET['departement_id'] == $dept['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="marketiste_id">
                    <option value="">Marketiste</option>
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
                <select class="form-select" name="statut">
                    <option value="">Statut</option>
                    <option value="PLANIFIE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'PLANIFIE') ? 'selected' : ''; ?>>Planifié</option>
                    <option value="CONFIRME" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'CONFIRME') ? 'selected' : ''; ?>>Confirmé</option>
                    <option value="REALISE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'REALISE') ? 'selected' : ''; ?>>Réalisé</option>
                    <option value="ANNULE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'ANNULE') ? 'selected' : ''; ?>>Annulé</option>
                    <option value="REPORTE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'REPORTE') ? 'selected' : ''; ?>>Reporté</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_rdv" 
                       placeholder="Date précise"
                       value="<?php echo isset($_GET['date_rdv']) ? $_GET['date_rdv'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Prospect</th>
                        <th>Département</th>
                        <th>Marketiste</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($rendezVous) && count($rendezVous) > 0): ?>
                        <?php foreach($rendezVous as $rdv): ?>
                            <tr>
                                <td><?php echo $rdv['id']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($rdv['date_rdv'])); ?></td>
                                <td><?php echo date('H:i', strtotime($rdv['heure_rdv'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($rdv['prospect_nom']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($rdv['prospect_telephone']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($rdv['departement_nom']); ?></td>
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
                                    <div class="btn-group">
                                        <a href="/educrm/rendezvous/<?php echo $rdv['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/educrm/rendezvous/<?php echo $rdv['id']; ?>/edit" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
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
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucun rendez-vous trouvé
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>