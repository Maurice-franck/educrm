<div class="mb-4">
    <h2>
        <i class="fas fa-eye me-2"></i>Détail du prospect
    </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/educrm/chef-departement/prospects">Prospects</a></li>
            <li class="breadcrumb-item active">Détail</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th style="width:220px;">Nom complet</th>
                <td><?php echo htmlspecialchars(($prospect->nom ?? '') . ' ' . ($prospect->prenom ?? '')); ?></td>
            </tr>
            <tr>
                <th>Téléphone</th>
                <td><?php echo htmlspecialchars($prospect->telephone ?? ''); ?></td>
            </tr>
            <tr>
                <th>WhatsApp</th>
                <td><?php echo htmlspecialchars($prospect->whatsapp ?? ''); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($prospect->email ?? ''); ?></td>
            </tr>
            <tr>
                <th>Ville</th>
                <td><?php echo htmlspecialchars($prospect->ville ?? ''); ?></td>
            </tr>
            <tr>
                <th>Niveau académique</th>
                <td><?php echo htmlspecialchars($prospect->niveau_academique ?? ''); ?></td>
            </tr>
            <tr>
                <th>Spécialité</th>
                <td><?php echo htmlspecialchars($prospect->specialite_nom ?? ''); ?></td>
            </tr>
            <tr>
                <th>Marketiste référent</th>
                <td><?php echo htmlspecialchars($prospect->marketiste_nom ?? 'Non affecté'); ?></td>
            </tr>
            <tr>
                <th>Statut</th>
                <td><?php echo htmlspecialchars($prospect->statut ?? ''); ?></td>
            </tr>
            <tr>
                <th>Commentaire</th>
                <td><?php echo htmlspecialchars($prospect->commentaire ?? ''); ?></td>
            </tr>
        </table>

        <a href="/educrm/chef-departement/prospects/<?php echo $prospect->id; ?>/edit" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        <a href="/educrm/chef-departement/supervision/create" class="btn btn-success">
            <i class="fas fa-phone me-2"></i>Ajouter une relance
        </a>
        <a href="/educrm/chef-departement/prospects" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>
