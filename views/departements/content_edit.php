<div class="mb-4">
    <h2>
        <i class="fas fa-edit me-2"></i>Modifier le département
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/departements">Départements</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/departements/<?php echo $this->departement->id; ?>/update" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">
                    <i class="fas fa-building me-1"></i>Nom du département <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nom" name="nom" 
                       value="<?php echo htmlspecialchars($this->departement->nom); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left me-1"></i>Description
                </label>
                <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($this->departement->description); ?></textarea>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Modifier
                </button>
                <a href="/educrm/departements" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>