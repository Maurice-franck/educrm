<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-list me-2"></i>Toutes les relances du département
    </h2>
    <div>
        <a href="/educrm/chef-departement/supervision" class="btn btn-secondary me-2">
            <i class="fas fa-chart-line me-2"></i>Supervision
        </a>
        <a href="/educrm/chef-departement/supervision/create" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Nouvelle relance
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/educrm/chef-departement/supervision/all" class="row g-3">
            <div class="col-md-2">
                <select class="form-select" name="marketiste_id">
                    <option value="">Marketiste</option>
                    <?php foreach ($marketistes as $m): ?>
                        <option value="<?php echo $m['id']; ?>"
                            <?php echo (isset($_GET['marketiste_id']) && $_GET['marketiste_id'] == $m['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($m['nom'] . ' ' . $m['prenom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="type_relance">
                    <option value="">Type</option>
                    <option value="APPEL" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'APPEL') ? 'selected' : ''; ?>>Appel</option>
                    <option value="WHATSAPP" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'WHATSAPP') ? 'selected' : ''; ?>>WhatsApp</option>
                    <option value="SMS" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'SMS') ? 'selected' : ''; ?>>SMS</option>
                    <option value="EMAIL" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'EMAIL') ? 'selected' : ''; ?>>Email</option>
                    <option value="VISITE" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'VISITE') ? 'selected' : ''; ?>>Visite</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="resultat">
                    <option value="">Résultat</option>
                    <option value="REPONDU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'REPONDU') ? 'selected' : ''; ?>>Répondu</option>
                    <option value="PAS_REPONDU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'PAS_REPONDU') ? 'selected' : ''; ?>>Pas répondu</option>
                    <option value="RDV_OBTENU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'RDV_OBTENU') ? 'selected' : ''; ?>>RDV obtenu</option>
                    <option value="A_RAPPELER" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'A_RAPPELER') ? 'selected' : ''; ?>>À rappeler</option>
                    <option value="REFUSE" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'REFUSE') ? 'selected' : ''; ?>>Refusé</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_debut"
                       placeholder="Date début"
                       value="<?php echo isset($_GET['date_debut']) ? $_GET['date_debut'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_fin"
                       placeholder="Date fin"
                       value="<?php echo isset($_GET['date_fin']) ? $_GET['date_fin'] : ''; ?>">
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
                        <th>Date</th>
                        <th>Prospect</th>
                        <th>Marketiste</th>
                        <th>Téléphone</th>
                        <th>Type</th>
                        <th>Résultat</th>
                        <th>Commentaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($relances) && count($relances) > 0): ?>
                        <?php foreach ($relances as $relance): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($relance['date_relance'])); ?></td>
                                <td><?php echo htmlspecialchars($relance['prospect_nom'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($relance['marketiste_nom'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($relance['prospect_telephone'] ?? ''); ?></td>
                                <td><?php echo $relance['type_relance']; ?></td>
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
                                <td><?php echo htmlspecialchars($relance['commentaire'] ?? ''); ?></td>
                                <td>
                                    <a href="/educrm/chef-departement/supervision/<?php echo $relance['id']; ?>/edit"
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucune relance trouvée
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
