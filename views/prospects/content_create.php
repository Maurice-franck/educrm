<div class="mb-4">
    <h2>
        <i class="fas fa-user-plus me-2"></i>Ajouter un prospect
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/prospects">Prospects</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/prospects/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" class="form-control" name="nom" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom *</label>
                    <input type="text" class="form-control" name="prenom" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sexe</label>
                    <select class="form-select" name="sexe">
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Téléphone *</label>
                    <input type="tel" class="form-control" name="telephone" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">WhatsApp</label>
                    <input type="tel" class="form-control" name="whatsapp">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ville</label>
                    <input type="text" class="form-control" name="ville">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Niveau académique</label>
                    <input type="text" class="form-control" name="niveau_academique" 
                           placeholder="BAC, LICENCE, MASTER...">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Spécialité *</label>
                    <select class="form-select" name="specialite_id" required>
                        <option value="">Sélectionner une spécialité</option>
                        <?php foreach($specialites as $spec): ?>
                            <option value="<?php echo $spec['id']; ?>">
                                <?php echo htmlspecialchars($spec['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Source marketing *</label>
                    <select class="form-select" name="source_id" required>
                        <option value="">Sélectionner une source</option>
                        <?php foreach($sources as $src): ?>
                            <option value="<?php echo $src['id']; ?>">
                                <?php echo htmlspecialchars($src['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Marketiste</label>
                    <select class="form-select" name="marketiste_id">
                        <option value="">Sélectionner un marketiste</option>
                        <?php foreach($marketistes as $mkt): ?>
                            <?php if($mkt['role'] == 'MARKETISTE'): ?>
                                <option value="<?php echo $mkt['id']; ?>">
                                    <?php echo htmlspecialchars($mkt['nom'] . ' ' . $mkt['prenom']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Statut *</label>
                    <select class="form-select" name="statut" required>
                        <option value="NOUVEAU">Nouveau</option>
                        <option value="CONTACTE">Contacté</option>
                        <option value="RELANCE">Relance</option>
                        <option value="RDV_PROGRAMME">RDV Programmé</option>
                        <option value="INTERESSE">Intéressé</option>
                        <option value="INSCRIT">Inscrit</option>
                        <option value="ABANDONNE">Abandonné</option>
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
                <a href="/educrm/prospects" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>