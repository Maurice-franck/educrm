<div class="mb-4">
    <h2>
        <i class="fas fa-key me-2"></i>Réinitialiser le mot de passe
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/utilisateurs">Utilisateurs</a></li>
            <li class="breadcrumb-item active">Réinitialiser mot de passe</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-user me-2"></i>
            Utilisateur : <?php echo htmlspecialchars($this->utilisateur->nom . ' ' . $this->utilisateur->prenom); ?>
        </h5>
    </div>
    <div class="card-body">
        <form action="/educrm/utilisateurs/<?php echo $this->utilisateur->id; ?>/reset-password" method="POST">
            <div class="mb-3">
                <label for="new_password" class="form-label">
                    <i class="fas fa-lock me-1"></i>Nouveau mot de passe <span class="text-danger">*</span>
                </label>
                <input type="password" class="form-control" id="new_password" name="new_password" 
                       required minlength="6">
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle me-1"></i>Minimum 6 caractères
                </small>
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">
                    <i class="fas fa-check-circle me-1"></i>Confirmer le mot de passe <span class="text-danger">*</span>
                </label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Attention : L'utilisateur devra utiliser ce nouveau mot de passe pour se connecter.
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key me-2"></i>Réinitialiser
                </button>
                <a href="/educrm/utilisateurs" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Validation du mot de passe en temps réel
document.getElementById('confirm_password').addEventListener('input', function() {
    var password = document.getElementById('new_password').value;
    var confirm = this.value;
    
    if (password !== confirm) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});
</script>