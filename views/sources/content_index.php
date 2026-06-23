<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-chart-line me-2"></i>Gestion des Sources Marketing
    </h2>
    <a href="/educrm/sources/create" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Ajouter une source
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Les sources marketing vous permettent de connaître l'origine de vos prospects (Facebook, WhatsApp, Site Web, etc.)
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Liste des sources marketing</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Source marketing</th>
                        <th>Nombre de prospects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($sources) && count($sources) > 0): ?>
                        <?php foreach($sources as $source): ?>
                            <tr>
                                <td><?php echo $source['id']; ?></td>
                                <td>
                                    <strong>
                                        <i class="fas fa-tag me-1"></i>
                                        <?php echo htmlspecialchars($source['nom']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-users me-1"></i>
                                        <?php echo $source['total_prospects']; ?> prospect(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/educrm/sources/<?php echo $source['id']; ?>/edit" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/educrm/sources/<?php echo $source['id']; ?>/delete" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette source ?\n\nAttention : La suppression sera impossible si des prospects utilisent cette source.')"
                                           title="Supprimer"
                                           <?php echo $source['total_prospects'] > 0 ? 'disabled style="opacity:0.5;"' : ''; ?>>
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucune source marketing trouvée
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Suggestions de sources marketing -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-lightbulb me-2"></i>Sources marketing suggérées
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="badge bg-info p-2 m-1">Facebook Ads</div>
                <div class="badge bg-info p-2 m-1">Instagram Ads</div>
            </div>
            <div class="col-md-3">
                <div class="badge bg-success p-2 m-1">WhatsApp</div>
                <div class="badge bg-success p-2 m-1">TikTok</div>
            </div>
            <div class="col-md-3">
                <div class="badge bg-warning p-2 m-1">Site Web</div>
                <div class="badge bg-warning p-2 m-1">Google Ads</div>
            </div>
            <div class="col-md-3">
                <div class="badge bg-secondary p-2 m-1">Référence</div>
                <div class="badge bg-secondary p-2 m-1">Salon d'orientation</div>
            </div>
        </div>
    </div>
</div>