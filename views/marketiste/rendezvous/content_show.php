<div class="mb-4">
    <h2>
        <i class="fas fa-eye me-2"></i>Détail du rendez-vous
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/marketiste/rendezvous">Rendez-vous</a></li>
            <li class="breadcrumb-item active">Détail</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th style="width:200px;">Date</th>
                <td><?php echo date('d/m/Y', strtotime($rendezVous->date_rdv)); ?></td>
            </tr>
            <tr>
                <th>Heure</th>
                <td><?php echo date('H:i', strtotime($rendezVous->heure_rdv)); ?></td>
            </tr>
            <tr>
                <th>Lieu</th>
                <td><?php echo htmlspecialchars($rendezVous->lieu ?? ""); ?></td>
            </tr>
            <tr>
                <th>Objet</th>
                <td><?php echo htmlspecialchars($rendezVous->objet ?? ""); ?></td>
            </tr>
            <tr>
                <th>Statut</th>
                <td><?php echo htmlspecialchars($rendezVous->statut ?? ""); ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo htmlspecialchars($rendezVous->observation ?? ""); ?></td>
            </tr>
        </table>

        <a href="/educrm/marketiste/rendezvous/<?php echo $rendezVous->id; ?>/edit" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        <a href="/educrm/marketiste/rendezvous" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>
