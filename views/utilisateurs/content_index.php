<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
    </h2>
    <a href="/educrm/utilisateurs/create" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Ajouter un utilisateur
    </a>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Liste des utilisateurs</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($utilisateurs) && count($utilisateurs) > 0): ?>
                        <?php foreach($utilisateurs as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($user['telephone']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = '';
                                    $roleLabel = '';
                                    switch($user['role']) {
                                        case 'ADMIN':
                                            $badgeClass = 'bg-danger';
                                            $roleLabel = 'Administrateur';
                                            break;
                                        case 'MARKETISTE':
                                            $badgeClass = 'bg-warning';
                                            $roleLabel = 'Marketiste';
                                            break;
                                        case 'CHEF_DEPARTEMENT':
                                            $badgeClass = 'bg-info';
                                            $roleLabel = 'Chef de département';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo $roleLabel; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $user['statut'] == 'ACTIF' ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $user['statut'] == 'ACTIF' ? 'Actif' : 'Inactif'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($user['date_creation'])); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/educrm/utilisateurs/<?php echo $user['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Consulter">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/educrm/utilisateurs/<?php echo $user['id']; ?>/edit" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($user['statut'] == 'ACTIF'): ?>
                                            <a href="/educrm/utilisateurs/<?php echo $user['id']; ?>/deactivate" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir désactiver cet utilisateur ?')"
                                               title="Désactiver">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="/educrm/utilisateurs/<?php echo $user['id']; ?>/activate" 
                                               class="btn btn-sm btn-success" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir réactiver cet utilisateur ?')"
                                               title="Réactiver">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="/educrm/utilisateurs/<?php echo $user['id']; ?>/reset-password" 
                                           class="btn btn-sm btn-secondary" title="Réinitialiser mot de passe">
                                            <i class="fas fa-key"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Aucun utilisateur trouvé
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>