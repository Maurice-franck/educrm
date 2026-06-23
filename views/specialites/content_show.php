<div class="mb-4">
    <h2>
        <i class="fas fa-info-circle me-2"></i>Détails de la spécialité
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/specialites">Spécialités</a></li>
            <li class="breadcrumb-item active">Consulter</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-tag me-2"></i>
            <?php echo htmlspecialchars($this->specialite->nom); ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-hashtag me-1"></i>ID :
            </div>
            <div class="col-md-9"><?php echo $this->specialite->id; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-building me-1"></i>Département :
            </div>
            <div class="col-md-9">
                <span class="badge bg-secondary fs-6">
                    <?php echo htmlspecialchars($this->specialite->departement_nom); ?>
                </span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-tag me-1"></i>Spécialité :
            </div>
            <div class="col-md-9">
                <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($this->specialite->nom); ?></span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                <i class="fas fa-align-left me-1"></i>Description :
            </div>
            <div class="col-md-9">
                <?php echo !empty($this->specialite->description) ? nl2br(htmlspecialchars($this->specialite->description)) : '<em class="text-muted">Aucune description</em>'; ?>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white">
        <a href="/educrm/specialites" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
        <a href="/educrm/specialites/<?php echo $this->specialite->id; ?>/edit" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
    </div>
</div>