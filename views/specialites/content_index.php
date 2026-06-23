<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-tags me-2"></i>Gestion des Spécialités
    </h2>
    <a href="/educrm/specialites/create" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Ajouter une spécialité
    </a>
</div>

<!-- Filtres et recherche -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form method="GET" action="/educrm/specialites" class="row g-3">
                    <div class="col-10">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Rechercher par nom, description ou département..." 
                                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form method="GET" action="/educrm/specialites" class="row g-3">
                    <div class="col-10">
                        <select class="form-select" name="departement_id">
                            <option value="">Tous les départements</option>
                            <?php if(isset($departements) && !empty($departements)): ?>
                                <?php foreach($departements as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>" 
                                        <?php echo (isset($_GET['departement_id']) && $_GET['departement_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($dept['nom']); ?>
                                        (<?php echo isset($dept['total_specialites']) ? $dept['total_specialites'] : 0; ?> spé.)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Aucun département disponible</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if(isset($current_departement) && $current_departement): ?>
    <div class="alert alert-info mb-4">
        <i class="fas fa-building me-2"></i>
        <strong>Département :</strong> <?php echo htmlspecialchars($current_departement); ?>
        <a href="/educrm/specialites" class="float-end text-decoration-none">
            <i class="fas fa-times"></i> Effacer le filtre
        </a>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Liste des spécialités</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Département</th>
                        <th>Spécialité</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($specialites) && count($specialites) > 0): ?>
                        <?php foreach($specialites as $spec): ?>
                            <tr>
                                <td><?php echo $spec['id']; ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($spec['departement_nom']); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($spec['nom']); ?></strong>
                                </td>
                                <td>
                                    <?php 
                                    $desc = htmlspecialchars($spec['description']);
                                    echo strlen($desc) > 60 ? substr($desc, 0, 60) . '...' : ($desc ?: '<em class="text-muted">Aucune description</em>');
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/educrm/specialites/<?php echo $spec['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Consulter">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/educrm/specialites/<?php echo $spec['id']; ?>/edit" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/educrm/specialites/<?php echo $spec['id']; ?>/delete" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette spécialité ?')"
                                           title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucune spécialité trouvée
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>