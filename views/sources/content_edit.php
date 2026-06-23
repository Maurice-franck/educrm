<div class="mb-4">
    <h2>
        <i class="fas fa-edit me-2"></i>Modifier la source marketing
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/sources">Sources marketing</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/sources/<?php echo $this->source->id; ?>/update" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">
                    <i class="fas fa-tag me-1"></i>Nom de la source <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nom" name="nom" 
                       value="<?php echo htmlspecialchars($this->source->nom); ?>" required>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Modifier
                </button>
                <a href="/educrm/sources" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>