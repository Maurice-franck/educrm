<div class="mb-4">
    <h2>
        <i class="fas fa-calendar-plus me-2"></i>Nouveau rendez-vous
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/chef-departement/rendezvous">Rendez-vous</a></li>
            <li class="breadcrumb-item active">Nouveau</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="/educrm/chef-departement/rendezvous/store" method="POST">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Prospect *</label>
                    <select class="form-select" name="prospect_id" required>
                        <option value="">Sélectionner un prospect</option>
                        <?php foreach ($prospects as $p): ?>
                            <option value="<?php echo $p['id']; ?>">
                                <?php echo htmlspecialchars(($p['nom'] ?? '') . ' ' . ($p['prenom'] ?? '') . ' — ' . ($p['telephone'] ?? '') . ' (' . ($p['marketiste_nom'] ?? 'non affecté') . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($prospects)): ?>
                        <small class="text-danger">Aucun prospect n'existe encore dans votre département.</small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date du rendez-vous *</label>
                    <input type="date" class="form-control" name="date_rdv" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Heure *</label>
                    <input type="time" class="form-control" name="heure_rdv" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lieu</label>
                    <input type="text" class="form-control" name="lieu" placeholder="Campus, en ligne, etc.">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Statut *</label>
                    <select class="form-select" name="statut" required>
                        <option value="PLANIFIE">Planifié</option>
                        <option value="CONFIRME">Confirmé</option>
                        <option value="REALISE">Réalisé</option>
                        <option value="ANNULE">Annulé</option>
                        <option value="REPORTE">Reporté</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Objet</label>
                <input type="text" class="form-control" name="objet" placeholder="Objet du rendez-vous">
            </div>

            <div class="mb-3">
                <label class="form-label">Observation</label>
                <textarea class="form-control" name="observation" rows="3"></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
                <a href="/educrm/chef-departement/rendezvous" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>
