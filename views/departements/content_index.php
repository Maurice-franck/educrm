<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-building me-2"></i>Gestion des Départements
    </h2>
    <a href="/educrm/departements/create" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Ajouter un département
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/educrm/departements" class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Rechercher un département..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Liste des départements</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom du département</th>
                        <th>Description</th>
                        <th>Spécialités</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($departements) && count($departements) > 0): ?>
                        <?php foreach($departements as $dept): ?>
                            <tr>
                                <td><?php echo $dept['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($dept['nom']); ?></strong>
                                </td>
                                <td>
                                    <?php 
                                    $desc = htmlspecialchars($dept['description']);
                                    echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
                                    ?>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="fas fa-tag me-1"></i>
                                        <?php echo $dept['total_specialites']; ?> spécialité(s)
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($dept['date_creation'])); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/educrm/departements/<?php echo $dept['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Consulter">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/educrm/departements/<?php echo $dept['id']; ?>/edit" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/educrm/departements/<?php echo $dept['id']; ?>/delete" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?')"
                                           title="Supprimer"
                                           <?php echo $dept['total_specialites'] > 0 ? 'disabled style="opacity:0.5;"' : ''; ?>>
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucun département trouvé
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>