<?php
// views/parametres/content_index.php
// Variables disponibles : $utilisateur (tableau), $settings (tableau clé=>valeur)

$u = $utilisateur;
$roleLabels = [
    'ADMIN'            => ['label' => 'Administrateur', 'class' => 'bg-danger'],
    'MARKETISTE'       => ['label' => 'Marketiste',     'class' => 'bg-info text-dark'],
    'CHEF_DEPARTEMENT' => ['label' => 'Chef Dép.',      'class' => 'bg-warning text-dark'],
];
$roleInfo = $roleLabels[$u['role']] ?? ['label' => $u['role'], 'class' => 'bg-secondary'];

// Langue active pour afficher les libellés dans la bonne langue
$langue = $settings['app_langue'] ?? 'fr';

// Textes bilingues
$t = [
    'fr' => [
        'titre'        => 'Paramètres',
        'profil'       => 'Mon profil',
        'mdp'          => 'Mot de passe',
        'app'          => 'Application',
        'nom'          => 'Nom',
        'prenom'       => 'Prénom',
        'email'        => 'Email',
        'telephone'    => 'Téléphone',
        'role'         => 'Rôle',
        'role_info'    => 'Le rôle ne peut être modifié que par l\'administrateur.',
        'enregistrer'  => 'Enregistrer',
        'ancien_mdp'   => 'Ancien mot de passe',
        'nouveau_mdp'  => 'Nouveau mot de passe',
        'confirmer'    => 'Confirmer',
        'changer_mdp'  => 'Changer le mot de passe',
        'langue'       => 'Langue de l\'interface',
        'fuseau'       => 'Fuseau horaire',
        'app_nom'      => 'Nom de l\'application',
        'app_slogan'   => 'Slogan',
        'app_email'    => 'Email de l\'établissement',
        'app_tel'      => 'Téléphone de l\'établissement',
        'sauvegarder'  => 'Sauvegarder les paramètres',
        'depuis'       => 'Membre depuis le',
        'compte'       => 'Compte',
    ],
    'en' => [
        'titre'        => 'Settings',
        'profil'       => 'My profile',
        'mdp'          => 'Password',
        'app'          => 'Application',
        'nom'          => 'Last name',
        'prenom'       => 'First name',
        'email'        => 'Email',
        'telephone'    => 'Phone',
        'role'         => 'Role',
        'role_info'    => 'The role can only be changed by an administrator.',
        'enregistrer'  => 'Save',
        'ancien_mdp'   => 'Current password',
        'nouveau_mdp'  => 'New password',
        'confirmer'    => 'Confirm',
        'changer_mdp'  => 'Change password',
        'langue'       => 'Interface language',
        'fuseau'       => 'Timezone',
        'app_nom'      => 'Application name',
        'app_slogan'   => 'Slogan',
        'app_email'    => 'Institution email',
        'app_tel'      => 'Institution phone',
        'sauvegarder'  => 'Save settings',
        'depuis'       => 'Member since',
        'compte'       => 'Account',
    ],
][$langue] ?? [];

// Déterminer l'onglet actif selon l'ancre URL
$hash = '';
?>

<!-- Messages flash -->
<?php if (!empty($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="mb-4">
    <h2><i class="fas fa-cog me-2"></i><?= $t['titre'] ?></h2>
</div>

<div class="row g-4">

    <!-- ===== COLONNE GAUCHE : carte résumé ===== -->
    <div class="col-lg-3 col-md-4">
        <div class="card shadow-sm text-center p-4 h-100">
            <!-- Avatar initiales -->
            <div class="mb-3">
                <div class="rounded-circle bg-danger d-inline-flex align-items-center justify-content-center"
                     style="width:75px;height:75px;">
                    <span class="text-white fw-bold" style="font-size:1.6rem;">
                        <?= strtoupper(substr($u['prenom'], 0, 1) . substr($u['nom'], 0, 1)) ?>
                    </span>
                </div>
            </div>
            <h6 class="fw-bold mb-1"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></h6>
            <p class="text-muted small mb-2"><?= htmlspecialchars($u['email']) ?></p>
            <span class="badge <?= $roleInfo['class'] ?> mb-3"><?= $roleInfo['label'] ?></span>
            <hr class="my-2">
            <div class="text-start small text-muted">
                <div class="mb-2">
                    <i class="fas fa-phone me-2 text-primary"></i>
                    <?= htmlspecialchars($u['telephone'] ?? $_SESSION['user_telephone'] ?? '—') ?>
                </div>
                <div class="mb-2">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    <?= $t['depuis'] ?> <?= date('d/m/Y', strtotime($u['date_creation'])) ?>
                </div>
                <div>
                    <i class="fas fa-circle me-2 <?= $u['statut'] === 'ACTIF' ? 'text-success' : 'text-danger' ?>"
                       style="font-size:0.55rem; vertical-align:middle;"></i>
                    <?= $t['compte'] ?> <strong><?= $u['statut'] ?></strong>
                </div>
            </div>

            <hr class="my-3">
            <!-- Infos app actuelle -->
            <div class="text-start small">
                <div class="mb-1">
                    <i class="fas fa-globe me-2 text-secondary"></i>
                    <?= strtoupper($settings['app_langue'] ?? 'FR') ?>
                    &nbsp;/&nbsp;
                    <?= htmlspecialchars($settings['app_fuseau'] ?? 'Africa/Douala') ?>
                </div>
                <div class="text-muted">
                    <i class="fas fa-tag me-2 text-secondary"></i>
                    <?= htmlspecialchars($settings['app_nom'] ?? 'EduCRM') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== COLONNE DROITE : onglets ===== -->
    <div class="col-lg-9 col-md-8">

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" id="paramTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-profil-link"
                   data-bs-toggle="tab" href="#tab-profil" role="tab">
                    <i class="fas fa-user me-1"></i><?= $t['profil'] ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-mdp-link"
                   data-bs-toggle="tab" href="#tab-mdp" role="tab">
                    <i class="fas fa-lock me-1"></i><?= $t['mdp'] ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-app-link"
                   data-bs-toggle="tab" href="#tab-app" role="tab">
                    <i class="fas fa-sliders-h me-1"></i><?= $t['app'] ?>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ==============================
                 ONGLET 1 : PROFIL
            ============================== -->
            <div class="tab-pane fade show active" id="tab-profil" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold border-bottom">
                        <i class="fas fa-user-circle me-2 text-primary"></i>
                        <?= $t['profil'] ?>
                    </div>
                    <div class="card-body">
                        <form action="/educrm/parametres/profil" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <?= $t['nom'] ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nom" class="form-control"
                                           value="<?= htmlspecialchars($u['nom']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <?= $t['prenom'] ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="prenom" class="form-control"
                                           value="<?= htmlspecialchars($u['prenom']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <?= $t['email'] ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control"
                                           value="<?= htmlspecialchars($u['email']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><?= $t['telephone'] ?></label>
                                    <input type="text" name="telephone" class="form-control"
                                           value="<?= htmlspecialchars($u['telephone'] ?? '') ?>"
                                           placeholder="Ex: 677001122">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold"><?= $t['role'] ?></label>
                                    <input type="text" class="form-control bg-light"
                                           value="<?= $roleInfo['label'] ?>" readonly>
                                    <small class="text-muted"><?= $t['role_info'] ?></small>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i><?= $t['enregistrer'] ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==============================
                 ONGLET 2 : MOT DE PASSE
            ============================== -->
            <div class="tab-pane fade" id="tab-mdp" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold border-bottom">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>
                        <?= $t['changer_mdp'] ?>
                    </div>
                    <div class="card-body">
                        <form action="/educrm/parametres/mot-de-passe" method="POST">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <?= $t['ancien_mdp'] ?> <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="ancien_mot_de_passe"
                                               class="form-control" id="ancienMdp" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="toggleMdp('ancienMdp',this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <?= $t['nouveau_mdp'] ?> <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="nouveau_mot_de_passe"
                                               class="form-control" id="nouveauMdp"
                                               required minlength="6">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="toggleMdp('nouveauMdp',this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">
                                        <?= $langue === 'en' ? 'Minimum 6 characters.' : 'Minimum 6 caractères.' ?>
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <?= $t['confirmer'] ?> <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="confirmer_mot_de_passe"
                                               class="form-control" id="confirmerMdp" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="toggleMdp('confirmerMdp',this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-lock me-1"></i><?= $t['changer_mdp'] ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==============================
                 ONGLET 3 : APPLICATION
            ============================== -->
            <div class="tab-pane fade" id="tab-app" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold border-bottom">
                        <i class="fas fa-sliders-h me-2 text-primary"></i>
                        <?= $t['app'] ?>
                    </div>
                    <div class="card-body">
                        <form action="/educrm/parametres/application" method="POST">
                            <div class="row g-3">

                                <!-- Langue -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><?= $t['langue'] ?></label>
                                    <select name="app_langue" class="form-select">
                                        <option value="fr" <?= ($settings['app_langue'] ?? 'fr') === 'fr' ? 'selected' : '' ?>>
                                            🇫🇷 Français
                                        </option>
                                        <option value="en" <?= ($settings['app_langue'] ?? 'fr') === 'en' ? 'selected' : '' ?>>
                                            🇬🇧 English
                                        </option>
                                    </select>
                                </div>

                                <!-- Fuseau horaire -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><?= $t['fuseau'] ?></label>
                                    <select name="app_fuseau" class="form-select">
                                        <?php
                                        // Fuseaux africains en priorité, puis les autres
                                        $prioritaires = [
                                            'Africa/Douala'     => 'Douala (Cameroun) UTC+1',
                                            'Africa/Lagos'      => 'Lagos (Nigeria) UTC+1',
                                            'Africa/Abidjan'    => 'Abidjan (Côte d\'Ivoire) UTC+0',
                                            'Africa/Dakar'      => 'Dakar (Sénégal) UTC+0',
                                            'Africa/Nairobi'    => 'Nairobi (Kenya) UTC+3',
                                            'Africa/Johannesburg'=> 'Johannesburg UTC+2',
                                            'Europe/Paris'      => 'Paris UTC+1/+2',
                                            'Europe/London'     => 'Londres UTC+0/+1',
                                            'America/New_York'  => 'New York UTC-5/-4',
                                            'UTC'               => 'UTC',
                                        ];
                                        $fuseauActuel = $settings['app_fuseau'] ?? 'Africa/Douala';
                                        echo '<optgroup label="— Recommandés —">';
                                        foreach ($prioritaires as $tz => $label) {
                                            $sel = $fuseauActuel === $tz ? 'selected' : '';
                                            echo "<option value=\"$tz\" $sel>$label</option>";
                                        }
                                        echo '</optgroup>';
                                        echo '<optgroup label="— Tous les fuseaux —">';
                                        foreach (DateTimeZone::listIdentifiers() as $tz) {
                                            if (isset($prioritaires[$tz])) continue;
                                            $sel = $fuseauActuel === $tz ? 'selected' : '';
                                            echo "<option value=\"$tz\" $sel>$tz</option>";
                                        }
                                        echo '</optgroup>';
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><?= $t['app_nom'] ?></label>
                                    <input type="text" name="app_nom" class="form-control"
                                           value="<?= htmlspecialchars($settings['app_nom'] ?? 'EduCRM') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><?= $t['app_slogan'] ?></label>
                                    <input type="text" name="app_slogan" class="form-control"
                                           value="<?= htmlspecialchars($settings['app_slogan'] ?? '') ?>"
                                           placeholder="Ex: Gestion des prospects">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><?= $t['app_email'] ?></label>
                                    <input type="email" name="app_email" class="form-control"
                                           value="<?= htmlspecialchars($settings['app_email'] ?? '') ?>"
                                           placeholder="contact@etablissement.cm">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><?= $t['app_tel'] ?></label>
                                    <input type="text" name="app_telephone" class="form-control"
                                           value="<?= htmlspecialchars($settings['app_telephone'] ?? '') ?>"
                                           placeholder="Ex: +237 677 001 122">
                                </div>

                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i><?= $t['sauvegarder'] ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /#tab-app -->

        </div><!-- /.tab-content -->
    </div><!-- /.col -->
</div><!-- /.row -->

<script>
// Bouton œil - afficher/masquer mot de passe
function toggleMdp(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Ré-ouvrir le bon onglet après redirect (via ancre URL)
document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector('[href="' + hash + '"]');
        if (tab) {
            new bootstrap.Tab(tab).show();
        }
    }
});
</script>
