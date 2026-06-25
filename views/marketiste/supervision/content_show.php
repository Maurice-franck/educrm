<div class="mb-4">
    <h2>
        <i class="fas fa-eye me-2"></i>Détail de la relance
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/marketiste/supervision">Supervision</a></li>
            <li class="breadcrumb-item active">Détail</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th style="width:200px;">Prospect</th>
                <td><?php echo htmlspecialchars($relance->prospect_id ?? ""); ?></td>
            </tr>
            <tr>
                <th>Type de relance</th>
                <td><?php echo htmlspecialchars($relance->type_relance ?? ""); ?></td>
            </tr>
            <tr>
                <th>Résultat</th>
                <td><?php echo htmlspecialchars($relance->resultat ?? ""); ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo date('d/m/Y H:i', strtotime($relance->date_relance)); ?></td>
            </tr>
            <tr>
                <th>Commentaire</th>
                <td><?php echo htmlspecialchars($relance->commentaire ?? ""); ?></td>
            </tr>
        </table>

        <a href="/educrm/marketiste/supervision/<?php echo $relance->id; ?>/edit" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        <a href="/educrm/marketiste/supervision" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>
