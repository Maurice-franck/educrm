<div class="mb-4">
    <h2>
        <i class="fas fa-edit me-2"></i>Modifier la spécialité
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/specialites">Spécialités</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <?php if(isset($departements) && !empty($departements)): ?>
            <form action="/educrm/specialites/<?php echo $this->specialite->id; ?>/update" method="POST">
                <div class="mb-3">
                    <label for="departement_id" class="form-label">
                        <i class="fas fa-building me-1"></i>Département <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="departement_id" name="departement_id" required>
                        <option value="">Sélectionner un département</option>
                        <?php foreach($departements as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>"
                                <?php echo ($dept['id'] == $this->specialite->departement_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="nom" class="form-label">
                        <i class="fas fa-tag me-1"></i>Nom de la spécialité <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($this->specialite->nom); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-1"></i>Description
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($this->specialite->description); ?></textarea>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Modifier
                    </button>
                    <a href="/educrm/specialites" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Aucun département disponible !</strong><br>
                Veuillez d'abord <a href="/educrm/departements/create" class="alert-link">créer un département</a> avant de modifier des spécialités.
            </div>
            <a href="/educrm/specialites" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        <?php endif; ?>
    </div>
</div>