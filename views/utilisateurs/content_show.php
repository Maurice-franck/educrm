<div class="mb-4">
    <h2>
        <i class="fas fa-user-circle me-2"></i>Détails de l'utilisateur
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/utilisateurs">Utilisateurs</a></li>
            <li class="breadcrumb-item active">Consulter</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>Informations personnelles
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-hashtag me-1"></i>ID :
            </div>
            <div class="col-md-9"><?php echo $this->utilisateur->id; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-user me-1"></i>Nom complet :
            </div>
            <div class="col-md-9"><?php echo htmlspecialchars($this->utilisateur->nom . ' ' . $this->utilisateur->prenom); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-phone me-1"></i>Téléphone :
            </div>
            <div class="col-md-9"><?php echo htmlspecialchars($this->utilisateur->telephone); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-envelope me-1"></i>Email :
            </div>
            <div class="col-md-9"><?php echo htmlspecialchars($this->utilisateur->email); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-tag me-1"></i>Rôle :
            </div>
            <div class="col-md-9">
                <?php
                $roleLabel = '';
                $roleIcon = '';
                switch($this->utilisateur->role) {
                    case 'ADMIN':
                        $roleIcon = 'fa-crown';
                        $roleLabel = 'Administrateur';
                        break;
                    case 'MARKETISTE':
                        $roleIcon = 'fa-chart-line';
                        $roleLabel = 'Marketiste';
                        break;
                    case 'CHEF_DEPARTEMENT':
                        $roleIcon = 'fa-users';
                        $roleLabel = 'Chef de département';
                        break;
                }
                ?>
                <i class="fas <?php echo $roleIcon; ?> me-1"></i>
                <?php echo $roleLabel; ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-circle me-1"></i>Statut :
            </div>
            <div class="col-md-9">
                <span class="status-badge <?php echo $this->utilisateur->statut == 'ACTIF' ? 'status-active' : 'status-inactive'; ?>">
                    <i class="fas <?php echo $this->utilisateur->statut == 'ACTIF' ? 'fa-check-circle' : 'fa-ban'; ?> me-1"></i>
                    <?php echo $this->utilisateur->statut == 'ACTIF' ? 'Actif' : 'Inactif'; ?>
                </span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-calendar-alt me-1"></i>Date de création :
            </div>
            <div class="col-md-9"><?php echo date('d/m/Y H:i:s', strtotime($this->utilisateur->date_creation)); ?></div>
        </div>
    </div>
    <div class="card-footer bg-white">
        <a href="/educrm/utilisateurs" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
        <a href="/educrm/utilisateurs/<?php echo $this->utilisateur->id; ?>/edit" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
    </div>
</div>