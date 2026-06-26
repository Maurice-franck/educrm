<div class="mb-4">
    <h2>
        <i class="fas fa-plus-circle me-2"></i>Nouvelle relance
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/marketiste/supervision">Supervision</a></li>
            <li class="breadcrumb-item active">Nouvelle relance</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/marketiste/supervision/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prospect *</label>
                    <select class="form-select" name="prospect_id" required>
                        <option value="">Sélectionner un prospect</option>
                        <?php foreach ($prospects as $p): ?>
                            <option value="<?php echo $p['id']; ?>">
                                <?php echo htmlspecialchars(($p['nom'] ?? '') . ' ' . ($p['prenom'] ?? '') . ' — ' . ($p['telephone'] ?? '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($prospects)): ?>
                        <small class="text-danger">Aucun prospect ne vous est encore affecté.</small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Type de relance *</label>
                    <select class="form-select" name="type_relance" required>
                        <option value="APPEL">Appel</option>
                        <option value="WHATSAPP">WhatsApp</option>
                        <option value="SMS">SMS</option>
                        <option value="EMAIL">Email</option>
                        <option value="VISITE">Visite</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Résultat *</label>
                    <select class="form-select" name="resultat" required>
                        <option value="REPONDU">Répondu</option>
                        <option value="PAS_REPONDU">Pas répondu</option>
                        <option value="RDV_OBTENU">RDV obtenu</option>
                        <option value="A_RAPPELER">À rappeler</option>
                        <option value="REFUSE">Refusé</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Commentaire</label>
                <textarea class="form-control" name="commentaire" rows="3"></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
                <a href="/educrm/marketiste/supervision" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>
