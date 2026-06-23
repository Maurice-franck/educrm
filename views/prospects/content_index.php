<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-user-friends me-2"></i>Gestion des Prospects
    </h2>
    <div>
        <a href="/educrm/prospects/export<?php echo !empty($_GET) ? '?' . http_build_query($_GET) : ''; ?>" 
           class="btn btn-success me-2">
            <i class="fas fa-download me-2"></i>Exporter
        </a>
        <a href="/educrm/prospects/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajouter un prospect
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total</h5>
                <h2><?php echo $stats['total']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Nouveaux</h5>
                <h2><?php echo $stats['nouveau']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Inscrits</h5>
                <h2><?php echo $stats['inscrit']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">RDV Programmés</h5>
                <h2><?php echo $stats['rdv']; ?></h2>
            </div>
        </div>
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
        <form method="GET" action="/educrm/prospects" class="row g-3">
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
                <label class="form-label">Spécialité</label>
                <select class="form-select" name="specialite_id">
                    <option value="">Toutes</option>
                    <?php foreach($specialites as $spec): ?>
                        <option value="<?php echo $spec['id']; ?>"
                            <?php echo (isset($_GET['specialite_id']) && $_GET['specialite_id'] == $spec['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($spec['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Source</label>
                <select class="form-select" name="source_id">
                    <option value="">Toutes</option>
                    <?php foreach($sources as $src): ?>
                        <option value="<?php echo $src['id']; ?>"
                            <?php echo (isset($_GET['source_id']) && $_GET['source_id'] == $src['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($src['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select class="form-select" name="statut">
                    <option value="">Tous</option>
                    <option value="NOUVEAU" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'NOUVEAU') ? 'selected' : ''; ?>>Nouveau</option>
                    <option value="CONTACTE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'CONTACTE') ? 'selected' : ''; ?>>Contacté</option>
                    <option value="RELANCE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'RELANCE') ? 'selected' : ''; ?>>Relance</option>
                    <option value="RDV_PROGRAMME" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'RDV_PROGRAMME') ? 'selected' : ''; ?>>RDV Programmé</option>
                    <option value="INTERESSE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'INTERESSE') ? 'selected' : ''; ?>>Intéressé</option>
                    <option value="INSCRIT" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'INSCRIT') ? 'selected' : ''; ?>>Inscrit</option>
                    <option value="ABANDONNE" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'ABANDONNE') ? 'selected' : ''; ?>>Abandonné</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date début</label>
                <input type="date" class="form-control" name="date_debut" 
                       value="<?php echo isset($_GET['date_debut']) ? $_GET['date_debut'] : ''; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date fin</label>
                <input type="date" class="form-control" name="date_fin" 
                       value="<?php echo isset($_GET['date_fin']) ? $_GET['date_fin'] : ''; ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Recherche</label>
                <input type="text" class="form-control" name="search" 
                       placeholder="Nom, prénom, téléphone, email..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des prospects -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Liste des prospects</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Contact</th>
                        <th>Spécialité</th>
                        <th>Source</th>
                        <th>Marketiste</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($prospects) && count($prospects) > 0): ?>
                        <?php foreach($prospects as $prospect): ?>
                            <tr>
                                <td><?php echo $prospect['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($prospect['nom'] . ' ' . $prospect['prenom']); ?></strong>
                                </td>
                                <td>
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($prospect['telephone']); ?><br>
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($prospect['email']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($prospect['specialite_nom']); ?></td>
                                <td><?php echo htmlspecialchars($prospect['source_nom']); ?></td>
                                <td><?php echo htmlspecialchars($prospect['marketiste_nom']); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = '';
                                    switch($prospect['statut']) {
                                        case 'NOUVEAU': $badgeClass = 'bg-primary'; break;
                                        case 'CONTACTE': $badgeClass = 'bg-info'; break;
                                        case 'RELANCE': $badgeClass = 'bg-warning'; break;
                                        case 'RDV_PROGRAMME': $badgeClass = 'bg-secondary'; break;
                                        case 'INTERESSE': $badgeClass = 'bg-success'; break;
                                        case 'INSCRIT': $badgeClass = 'bg-success'; break;
                                        case 'ABANDONNE': $badgeClass = 'bg-danger'; break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo str_replace('_', ' ', $prospect['statut']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($prospect['date_creation'])); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/educrm/prospects/<?php echo $prospect['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Consulter">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/educrm/prospects/<?php echo $prospect['id']; ?>/edit" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#statutModal<?php echo $prospect['id']; ?>"
                                                title="Changer statut">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#marketisteModal<?php echo $prospect['id']; ?>"
                                                title="Réaffecter marketiste">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#specialiteModal<?php echo $prospect['id']; ?>"
                                                title="Réaffecter spécialité">
                                            <i class="fas fa-tag"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal Changer statut -->
                                    <div class="modal fade" id="statutModal<?php echo $prospect['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="/educrm/prospects/<?php echo $prospect['id']; ?>/change-statut" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Changer le statut</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <select class="form-select" name="statut" required>
                                                            <option value="NOUVEAU" <?php echo $prospect['statut'] == 'NOUVEAU' ? 'selected' : ''; ?>>Nouveau</option>
                                                            <option value="CONTACTE" <?php echo $prospect['statut'] == 'CONTACTE' ? 'selected' : ''; ?>>Contacté</option>
                                                            <option value="RELANCE" <?php echo $prospect['statut'] == 'RELANCE' ? 'selected' : ''; ?>>Relance</option>
                                                            <option value="RDV_PROGRAMME" <?php echo $prospect['statut'] == 'RDV_PROGRAMME' ? 'selected' : ''; ?>>RDV Programmé</option>
                                                            <option value="INTERESSE" <?php echo $prospect['statut'] == 'INTERESSE' ? 'selected' : ''; ?>>Intéressé</option>
                                                            <option value="INSCRIT" <?php echo $prospect['statut'] == 'INSCRIT' ? 'selected' : ''; ?>>Inscrit</option>
                                                            <option value="ABANDONNE" <?php echo $prospect['statut'] == 'ABANDONNE' ? 'selected' : ''; ?>>Abandonné</option>
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
                                    
                                    <!-- Modal Réaffecter marketiste -->
                                    <div class="modal fade" id="marketisteModal<?php echo $prospect['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="/educrm/prospects/<?php echo $prospect['id']; ?>/reassign-marketiste" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Réaffecter à un marketiste</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <select class="form-select" name="marketiste_id" required>
                                                            <option value="">Sélectionner un marketiste</option>
                                                            <?php foreach($marketistes as $mkt): ?>
                                                                <?php if($mkt['role'] == 'MARKETISTE'): ?>
                                                                    <option value="<?php echo $mkt['id']; ?>" 
                                                                        <?php echo $prospect['marketiste_id'] == $mkt['id'] ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($mkt['nom'] . ' ' . $mkt['prenom']); ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
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
                                    
                                    <!-- Modal Réaffecter spécialité -->
                                    <div class="modal fade" id="specialiteModal<?php echo $prospect['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="/educrm/prospects/<?php echo $prospect['id']; ?>/reassign-specialite" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Réaffecter à une spécialité</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <select class="form-select" name="specialite_id" required>
                                                            <option value="">Sélectionner une spécialité</option>
                                                            <?php foreach($specialites as $spec): ?>
                                                                <option value="<?php echo $spec['id']; ?>"
                                                                    <?php echo $prospect['specialite_id'] == $spec['id'] ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($spec['nom']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
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
                                    <i class="fas fa-info-circle me-2"></i>Aucun prospect trouvé
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>