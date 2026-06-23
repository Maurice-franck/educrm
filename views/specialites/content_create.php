<div class="mb-4">
    <h2>
        <i class="fas fa-plus-circle me-2"></i>Ajouter une spécialité
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/specialites">Spécialités</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <?php if(isset($departements) && count($departements) > 0): ?>
            <form action="/educrm/specialites/store" method="POST">
                <div class="mb-3">
                    <label for="departement_id" class="form-label">
                        <i class="fas fa-building me-1"></i>Département <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="departement_id" name="departement_id" required>
                        <option value="">Sélectionner un département</option>
                        <?php foreach($departements as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>">
                                <?php echo htmlspecialchars($dept['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="nom" class="form-label">
                        <i class="fas fa-tag me-1"></i>Nom de la spécialité <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle me-1"></i>Ex: Réseaux et Systèmes, Développement Web...
                    </small>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-1"></i>Description
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="5" 
                              placeholder="Description détaillée de la spécialité..."></textarea>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Exemples par département :</strong><br>
                    <strong>Informatique :</strong> Réseaux et Systèmes, Cybersécurité, Développement Web, IA et Data Science<br>
                    <strong>Gestion :</strong> Comptabilité, Marketing Digital, RH, Finance<br>
                    <strong>Génie Civil :</strong> BTP, Architecture, Urbanisme
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Ajouter
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
                Veuillez d'abord <a href="/educrm/departements/create" class="alert-link">créer un département</a> avant d'ajouter des spécialités.
            </div>
            <a href="/educrm/specialites" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        <?php endif; ?>
    </div>
</div>