<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-list me-2"></i>Toutes les relances
    </h2>
    <div>
        <a href="/educrm/relances/export<?php echo !empty($_GET) ? '?' . http_build_query($_GET) : ''; ?>" 
           class="btn btn-success me-2">
            <i class="fas fa-download me-2"></i>Exporter
        </a>
        <a href="/educrm/relances" class="btn btn-secondary">
            <i class="fas fa-chart-line me-2"></i>Dashboard
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/educrm/relances/all" class="row g-3">
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_relance" 
                       placeholder="Date précise"
                       value="<?php echo isset($_GET['date_relance']) ? $_GET['date_relance'] : ''; ?>">
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
                <select class="form-select" name="type_relance">
                    <option value="">Type</option>
                    <option value="APPEL" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'APPEL') ? 'selected' : ''; ?>>Appel</option>
                    <option value="WHATSAPP" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'WHATSAPP') ? 'selected' : ''; ?>>WhatsApp</option>
                    <option value="EMAIL" <?php echo (isset($_GET['type_relance']) && $_GET['type_relance'] == 'EMAIL') ? 'selected' : ''; ?>>Email</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="resultat">
                    <option value="">Résultat</option>
                    <option value="REPONDU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'REPONDU') ? 'selected' : ''; ?>>Répondu</option>
                    <option value="RDV_OBTENU" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'RDV_OBTENU') ? 'selected' : ''; ?>>RDV obtenu</option>
                    <option value="A_RAPPELER" <?php echo (isset($_GET['resultat']) && $_GET['resultat'] == 'A_RAPPELER') ? 'selected' : ''; ?>>À rappeler</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
            </div>
            <div class="col-md-2">
                <a href="/educrm/relances/all" class="btn btn-secondary w-100">
                    <i class="fas fa-undo me-2"></i>Réinitialiser
                </a>
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
                        <th>Prospect</th>
                        <th>Téléphone</th>
                        <th>Marketiste</th>
                        <th>Type</th>
                        <th>Résultat</th>
                        <th>Commentaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($relances) && count($relances) > 0): ?>
                        <?php foreach($relances as $relance): ?>
                            <tr>
                                <td><?php echo $relance['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($relance['date_relance'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($relance['prospect_nom']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($relance['specialite_nom']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($relance['prospect_telephone']); ?></td>
                                <td><?php echo htmlspecialchars($relance['marketiste_nom']); ?></td>
                                <td>
                                    <?php
                                    $icon = '';
                                    switch($relance['type_relance']) {
                                        case 'APPEL': $icon = 'fa-phone text-primary'; break;
                                        case 'WHATSAPP': $icon = 'fa-whatsapp text-success'; break;
                                        case 'EMAIL': $icon = 'fa-envelope text-info'; break;
                                        case 'SMS': $icon = 'fa-sms text-secondary'; break;
                                        case 'VISITE': $icon = 'fa-building text-warning'; break;
                                    }
                                    ?>
                                    <i class="fas <?php echo $icon; ?>"></i> <?php echo $relance['type_relance']; ?>
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
                                <td><?php echo htmlspecialchars(substr($relance['commentaire'], 0, 50)); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/educrm/relances/<?php echo $relance['id']; ?>/edit" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/educrm/relances/<?php echo $relance['id']; ?>/delete" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Supprimer cette relance ?')"
                                           title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Aucune relance trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>