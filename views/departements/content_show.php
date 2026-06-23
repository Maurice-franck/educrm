<div class="mb-4">
    <h2>
        <i class="fas fa-info-circle me-2"></i>Détails du département
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/departements">Départements</a></li>
            <li class="breadcrumb-item active">Consulter</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-building me-2"></i>
            <?php echo htmlspecialchars($this->departement->nom); ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-hashtag me-1"></i>ID :
            </div>
            <div class="col-md-9"><?php echo $this->departement->id; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-building me-1"></i>Nom :
            </div>
            <div class="col-md-9">
                <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($this->departement->nom); ?></span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-align-left me-1"></i>Description :
            </div>
            <div class="col-md-9">
                <?php echo !empty($this->departement->description) ? nl2br(htmlspecialchars($this->departement->description)) : '<em>Aucune description</em>'; ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-calendar-alt me-1"></i>Date de création :
            </div>
            <div class="col-md-9"><?php echo date('d/m/Y H:i:s', strtotime($this->departement->date_creation)); ?></div>
        </div>
    </div>
    <div class="card-footer bg-white">
        <a href="/educrm/departements" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
        <a href="/educrm/departements/<?php echo $this->departement->id; ?>/edit" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
    </div>
</div>