<div class="mb-4">
    <h2>
        <i class="fas fa-user-edit me-2"></i>Modifier l'utilisateur
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/utilisateurs">Utilisateurs</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/utilisateurs/<?php echo $this->utilisateur->id; ?>/update" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nom" class="form-label">
                        <i class="fas fa-user me-1"></i>Nom <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($this->utilisateur->nom); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="prenom" class="form-label">
                        <i class="fas fa-user me-1"></i>Prénom <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="prenom" name="prenom" 
                           value="<?php echo htmlspecialchars($this->utilisateur->prenom); ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telephone" class="form-label">
                        <i class="fas fa-phone me-1"></i>Téléphone <span class="text-danger">*</span>
                    </label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" 
                           value="<?php echo htmlspecialchars($this->utilisateur->telephone); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($this->utilisateur->email); ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">
                        <i class="fas fa-tag me-1"></i>Rôle <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="role" name="role" required onchange="toggleDepartementField()">
                        <option value="ADMIN" <?php echo $this->utilisateur->role == 'ADMIN' ? 'selected' : ''; ?>>
                            Administrateur
                        </option>
                        <option value="MARKETISTE" <?php echo $this->utilisateur->role == 'MARKETISTE' ? 'selected' : ''; ?>>
                            Marketiste
                        </option>
                        <option value="CHEF_DEPARTEMENT" <?php echo $this->utilisateur->role == 'CHEF_DEPARTEMENT' ? 'selected' : ''; ?>>
                            Chef de département
                        </option>
                    </select>
                </div>
            </div>

            <div class="row" id="departement_field" style="<?php echo $this->utilisateur->role == 'CHEF_DEPARTEMENT' ? '' : 'display:none;'; ?>">
                <div class="col-md-6 mb-3">
                    <label for="departement_id" class="form-label">
                        <i class="fas fa-building me-1"></i>Département <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="departement_id" name="departement_id">
                        <option value="">Sélectionner un département</option>
                        <?php foreach ($departements as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>"
                                <?php echo $this->utilisateur->departement_id == $dept['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle me-1"></i>Le chef ne supervisera que les prospects de ce département
                    </small>
                </div>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                Pour réinitialiser le mot de passe, utilisez le bouton dédié dans la liste des utilisateurs.
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Modifier
                </button>
                <a href="/educrm/utilisateurs" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDepartementField() {
    var role = document.getElementById('role').value;
    var field = document.getElementById('departement_field');
    var select = document.getElementById('departement_id');
    if (role === 'CHEF_DEPARTEMENT') {
        field.style.display = '';
        select.setAttribute('required', 'required');
    } else {
        field.style.display = 'none';
        select.removeAttribute('required');
        select.value = '';
    }
}
</script>