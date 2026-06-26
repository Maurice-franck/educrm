<div class="mb-4">
    <h2>
        <i class="fas fa-edit me-2"></i>Modifier le rendez-vous
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/chef-departement/rendezvous">Rendez-vous</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-light border mb-3">
            <i class="fas fa-user me-2"></i>Prospect : <strong><?php echo htmlspecialchars($rendezVous->prospect_nom ?? ''); ?></strong>
            &nbsp;&nbsp;
            <i class="fas fa-user-tie me-2"></i>Marketiste : <strong><?php echo htmlspecialchars($rendezVous->marketiste_nom ?? ''); ?></strong>
        </div>
        <form action="/educrm/chef-departement/rendezvous/<?php echo $rendezVous->id; ?>/update" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date du rendez-vous *</label>
                    <input type="date" class="form-control" name="date_rdv" value="<?php echo $rendezVous->date_rdv; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Heure *</label>
                    <input type="time" class="form-control" name="heure_rdv" value="<?php echo $rendezVous->heure_rdv; ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lieu</label>
                    <input type="text" class="form-control" name="lieu" value="<?php echo htmlspecialchars($rendezVous->lieu ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Statut *</label>
                    <select class="form-select" name="statut" required>
                        <option value="PLANIFIE" <?php echo $rendezVous->statut == 'PLANIFIE' ? 'selected' : ''; ?>>Planifié</option>
                        <option value="CONFIRME" <?php echo $rendezVous->statut == 'CONFIRME' ? 'selected' : ''; ?>>Confirmé</option>
                        <option value="REALISE" <?php echo $rendezVous->statut == 'REALISE' ? 'selected' : ''; ?>>Réalisé</option>
                        <option value="ANNULE" <?php echo $rendezVous->statut == 'ANNULE' ? 'selected' : ''; ?>>Annulé</option>
                        <option value="REPORTE" <?php echo $rendezVous->statut == 'REPORTE' ? 'selected' : ''; ?>>Reporté</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Objet</label>
                <input type="text" class="form-control" name="objet" value="<?php echo htmlspecialchars($rendezVous->objet ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Observation</label>
                <textarea class="form-control" name="observation" rows="3"><?php echo htmlspecialchars($rendezVous->observation ?? ''); ?></textarea>
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
