<div class="mb-4">
    <h2>
        <i class="fas fa-plus-circle me-2"></i>Ajouter une source marketing
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/sources">Sources marketing</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/sources/store" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">
                    <i class="fas fa-tag me-1"></i>Nom de la source <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nom" name="nom" 
                       placeholder="Ex: Facebook Ads, WhatsApp, Site Web..." required>
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Entrez le nom de la source marketing (unique)
                </small>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Exemples de sources marketing :</strong><br>
                <div class="mt-2">
                    <span class="badge bg-primary m-1">Facebook Ads</span>
                    <span class="badge bg-primary m-1">Instagram Ads</span>
                    <span class="badge bg-success m-1">WhatsApp</span>
                    <span class="badge bg-success m-1">TikTok</span>
                    <span class="badge bg-info m-1">Site Web</span>
                    <span class="badge bg-info m-1">Google Ads</span>
                    <span class="badge bg-warning m-1">Référence</span>
                    <span class="badge bg-warning m-1">Salon d'orientation</span>
                    <span class="badge bg-secondary m-1">Emailing</span>
                    <span class="badge bg-secondary m-1">Bouche à oreille</span>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Ajouter
                </button>
                <a href="/educrm/sources" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>