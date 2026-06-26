<div class="mb-4">
    <h2>
        <i class="fas fa-eye me-2"></i>Détail du rendez-vous
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/rendezvous">Rendez-vous</a></li>
            <li class="breadcrumb-item active">Détail</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations du rendez-vous</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width:200px;">Prospect</th>
                        <td>
                            <strong><?php echo htmlspecialchars($rendezVous->prospect_nom ?? ''); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($rendezVous->prospect_telephone ?? ''); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <th>Marketiste</th>
                        <td><?php echo htmlspecialchars($rendezVous->marketiste_nom ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><?php echo date('d/m/Y', strtotime($rendezVous->date_rdv)); ?></td>
                    </tr>
                    <tr>
                        <th>Heure</th>
                        <td><?php echo date('H:i', strtotime($rendezVous->heure_rdv)); ?></td>
                    </tr>
                    <tr>
                        <th>Lieu</th>
                        <td><?php echo htmlspecialchars($rendezVous->lieu ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Objet</th>
                        <td><?php echo htmlspecialchars($rendezVous->objet ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td>
                            <?php
                            $badgeClass = '';
                            switch($rendezVous->statut) {
                                case 'PLANIFIE':  $badgeClass = 'bg-warning';   break;
                                case 'CONFIRME':  $badgeClass = 'bg-info';      break;
                                case 'REALISE':   $badgeClass = 'bg-success';   break;
                                case 'ANNULE':    $badgeClass = 'bg-danger';    break;
                                case 'REPORTE':   $badgeClass = 'bg-secondary'; break;
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars($rendezVous->statut ?? ''); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Observation</th>
                        <td><?php echo nl2br(htmlspecialchars($rendezVous->observation ?? '')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="/educrm/rendezvous/<?php echo $rendezVous->id; ?>/edit"
                   class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>

                <!-- Changer statut -->
                <button type="button" class="btn btn-secondary"
                        data-bs-toggle="modal" data-bs-target="#statutModal">
                    <i class="fas fa-exchange-alt me-2"></i>Changer le statut
                </button>

                <!-- Supprimer -->
                <button type="button" class="btn btn-danger"
                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>

                <a href="/educrm/rendezvous/all" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Changer statut -->
<div class="modal fade" id="statutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/educrm/rendezvous/<?php echo $rendezVous->id; ?>/change-statut" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Changer le statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" name="statut" required>
                        <option value="PLANIFIE"  <?php echo $rendezVous->statut == 'PLANIFIE'  ? 'selected' : ''; ?>>Planifié</option>
                        <option value="CONFIRME"  <?php echo $rendezVous->statut == 'CONFIRME'  ? 'selected' : ''; ?>>Confirmé</option>
                        <option value="REALISE"   <?php echo $rendezVous->statut == 'REALISE'   ? 'selected' : ''; ?>>Réalisé</option>
                        <option value="ANNULE"    <?php echo $rendezVous->statut == 'ANNULE'    ? 'selected' : ''; ?>>Annulé</option>
                        <option value="REPORTE"   <?php echo $rendezVous->statut == 'REPORTE'   ? 'selected' : ''; ?>>Reporté</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Supprimer -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="fas fa-trash me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimer ce rendez-vous ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="/educrm/rendezvous/<?php echo $rendezVous->id; ?>/delete" method="POST" style="display:inline;">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
