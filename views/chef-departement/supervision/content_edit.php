<div class="mb-4">
    <h2>
        <i class="fas fa-edit me-2"></i>Modifier la relance
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/chef-departement/supervision">Supervision</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-light border mb-3">
            <i class="fas fa-user me-2"></i>Prospect : <strong><?php echo htmlspecialchars($relance->prospect_nom ?? ''); ?></strong>
            &nbsp;&nbsp;
            <i class="fas fa-user-tie me-2"></i>Marketiste : <strong><?php echo htmlspecialchars($relance->marketiste_nom ?? ''); ?></strong>
        </div>
        <form action="/educrm/chef-departement/supervision/<?php echo $relance->id; ?>/update" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Type de relance *</label>
                    <select class="form-select" name="type_relance" required>
                        <option value="APPEL" <?php echo $relance->type_relance == 'APPEL' ? 'selected' : ''; ?>>Appel</option>
                        <option value="WHATSAPP" <?php echo $relance->type_relance == 'WHATSAPP' ? 'selected' : ''; ?>>WhatsApp</option>
                        <option value="SMS" <?php echo $relance->type_relance == 'SMS' ? 'selected' : ''; ?>>SMS</option>
                        <option value="EMAIL" <?php echo $relance->type_relance == 'EMAIL' ? 'selected' : ''; ?>>Email</option>
                        <option value="VISITE" <?php echo $relance->type_relance == 'VISITE' ? 'selected' : ''; ?>>Visite</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Résultat *</label>
                    <select class="form-select" name="resultat" required>
                        <option value="REPONDU" <?php echo $relance->resultat == 'REPONDU' ? 'selected' : ''; ?>>Répondu</option>
                        <option value="PAS_REPONDU" <?php echo $relance->resultat == 'PAS_REPONDU' ? 'selected' : ''; ?>>Pas répondu</option>
                        <option value="RDV_OBTENU" <?php echo $relance->resultat == 'RDV_OBTENU' ? 'selected' : ''; ?>>RDV obtenu</option>
                        <option value="A_RAPPELER" <?php echo $relance->resultat == 'A_RAPPELER' ? 'selected' : ''; ?>>À rappeler</option>
                        <option value="REFUSE" <?php echo $relance->resultat == 'REFUSE' ? 'selected' : ''; ?>>Refusé</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Commentaire</label>
                <textarea class="form-control" name="commentaire" rows="3"><?php echo htmlspecialchars($relance->commentaire ?? ''); ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
                <a href="/educrm/chef-departement/supervision" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>
