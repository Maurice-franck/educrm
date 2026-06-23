<div class="mb-4">
    <h2>
        <i class="fas fa-plus-circle me-2"></i>Ajouter un département
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/departements">Départements</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/departements/store" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">
                    <i class="fas fa-building me-1"></i>Nom du département <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nom" name="nom" required>
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle me-1"></i>Ex: Informatique, Gestion, Génie Civil...
                </small>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left me-1"></i>Description
                </label>
                <textarea class="form-control" id="description" name="description" rows="5"></textarea>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Ajouter
                </button>
                <a href="/educrm/departements" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>