<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-user-friends me-2"></i>Prospects du département
    </h2>
    <div>
        <a href="/educrm/chef-departement/prospects/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajouter un prospect
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
        <form method="GET" action="/educrm/chef-departement/prospects" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Recherche</label>
                <input type="text" class="form-control" name="search"
                       placeholder="Nom, téléphone, email..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Spécialité</label>
                <select class="form-select" name="specialite_id">
                    <option value="">Toutes</option>
                    <?php foreach ($specialites as $spec): ?>
                        <option value="<?php echo $spec['id']; ?>"
                            <?php echo (isset($_GET['specialite_id']) && $_GET['specialite_id'] == $spec['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($spec['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
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
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
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
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Spécialité</th>
                        <th>Marketiste</th>
                        <th>Source</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($prospects) && count($prospects) > 0): ?>
                        <?php foreach ($prospects as $prospect): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(($prospect['nom'] ?? '') . ' ' . ($prospect['prenom'] ?? '')); ?></td>
                                <td><?php echo htmlspecialchars($prospect['telephone'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($prospect['specialite_nom'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($prospect['marketiste_nom'] ?? 'Non affecté'); ?></td>
                                <td><?php echo htmlspecialchars($prospect['source_nom'] ?? ''); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = '';
                                    switch ($prospect['statut']) {
                                        case 'NOUVEAU': $badgeClass = 'bg-primary'; break;
                                        case 'CONTACTE': $badgeClass = 'bg-info'; break;
                                        case 'RELANCE': $badgeClass = 'bg-secondary'; break;
                                        case 'RDV_PROGRAMME': $badgeClass = 'bg-warning'; break;
                                        case 'INTERESSE': $badgeClass = 'bg-primary'; break;
                                        case 'INSCRIT': $badgeClass = 'bg-success'; break;
                                        case 'ABANDONNE': $badgeClass = 'bg-danger'; break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo str_replace('_', ' ', $prospect['statut']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/educrm/chef-departement/prospects/<?php echo $prospect['id']; ?>"
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/educrm/chef-departement/prospects/<?php echo $prospect['id']; ?>/edit"
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
                                    </div>

                                    <!-- Modal Changer statut -->
                                    <div class="modal fade" id="statutModal<?php echo $prospect['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="/educrm/chef-departement/prospects/<?php echo $prospect['id']; ?>/change-statut" method="POST">
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
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucun prospect dans votre département pour le moment
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
