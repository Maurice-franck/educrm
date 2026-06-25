<div class="mb-4">
    <h2>
        <i class="fas fa-edit me-2"></i>Modifier le prospect
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/marketiste/prospects">Prospects</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/marketiste/prospects/<?php echo $prospect->id; ?>/update" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" class="form-control" name="nom" value="<?php echo htmlspecialchars($prospect->nom ?? ""); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom *</label>
                    <input type="text" class="form-control" name="prenom" value="<?php echo htmlspecialchars($prospect->prenom ?? ""); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sexe</label>
                    <select class="form-select" name="sexe">
                        <option value="M" <?php echo $prospect->sexe == 'M' ? 'selected' : ''; ?>>Masculin</option>
                        <option value="F" <?php echo $prospect->sexe == 'F' ? 'selected' : ''; ?>>Féminin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Téléphone *</label>
                    <input type="tel" class="form-control" name="telephone" value="<?php echo htmlspecialchars($prospect->telephone ?? ""); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">WhatsApp</label>
                    <input type="tel" class="form-control" name="whatsapp" value="<?php echo htmlspecialchars($prospect->whatsapp ?? ""); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($prospect->email ?? ""); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ville</label>
                    <input type="text" class="form-control" name="ville" value="<?php echo htmlspecialchars($prospect->ville ?? ""); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Niveau académique</label>
                    <input type="text" class="form-control" name="niveau_academique" value="<?php echo htmlspecialchars($prospect->niveau_academique ?? ""); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Spécialité *</label>
                    <select class="form-select" name="specialite_id" required>
                        <option value="">Sélectionner une spécialité</option>
                        <?php foreach ($specialites as $spec): ?>
                            <option value="<?php echo $spec['id']; ?>" <?php echo $prospect->specialite_id == $spec['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($spec['nom'] ?? ""); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Source marketing *</label>
                    <select class="form-select" name="source_id" required>
                        <option value="">Sélectionner une source</option>
                        <?php foreach ($sources as $src): ?>
                            <option value="<?php echo $src['id']; ?>" <?php echo $prospect->source_id == $src['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($src['nom'] ?? ""); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Statut *</label>
                <select class="form-select" name="statut" required>
                    <option value="NOUVEAU" <?php echo $prospect->statut == 'NOUVEAU' ? 'selected' : ''; ?>>Nouveau</option>
                    <option value="CONTACTE" <?php echo $prospect->statut == 'CONTACTE' ? 'selected' : ''; ?>>Contacté</option>
                    <option value="RELANCE" <?php echo $prospect->statut == 'RELANCE' ? 'selected' : ''; ?>>Relance</option>
                    <option value="RDV_PROGRAMME" <?php echo $prospect->statut == 'RDV_PROGRAMME' ? 'selected' : ''; ?>>RDV Programmé</option>
                    <option value="INTERESSE" <?php echo $prospect->statut == 'INTERESSE' ? 'selected' : ''; ?>>Intéressé</option>
                    <option value="INSCRIT" <?php echo $prospect->statut == 'INSCRIT' ? 'selected' : ''; ?>>Inscrit</option>
                    <option value="ABANDONNE" <?php echo $prospect->statut == 'ABANDONNE' ? 'selected' : ''; ?>>Abandonné</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Commentaire</label>
                <textarea class="form-control" name="commentaire" rows="3"><?php echo htmlspecialchars($prospect->commentaire ?? ""); ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
                <a href="/educrm/marketiste/prospects" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>
