<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduCRM — Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:       #0A1628;
            --navy-mid:   #1E3A5F;
            --navy-light: #2D5186;
            --gold:       #F0A500;
            --gold-light: #FFB930;
            --white:      #F8F9FA;
            --muted:      #8FA3BF;
            --card-bg:    #ffffff;
            --error:      #E74C3C;
            --success:    #27AE60;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            overflow: hidden;
        }

        /* ─── LAYOUT SPLIT ─── */
        .login-wrapper {
            display: flex;
            height: 100vh;
        }

        /* ─── PANNEAU GAUCHE ─── */
        .panel-left {
            flex: 1;
            background: linear-gradient(145deg, var(--navy) 0%, var(--navy-mid) 60%, var(--navy-light) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 4rem;
            position: relative;
            overflow: hidden;
        }

        /* Cercles décoratifs */
        .panel-left::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            border: 1px solid rgba(240,165,0,0.12);
            top: -100px; left: -100px;
        }
        .panel-left::after {
            content: '';
            position: absolute;
            width: 350px; height: 350px;
            border-radius: 50%;
            border: 1px solid rgba(240,165,0,0.08);
            bottom: -80px; right: -80px;
        }

        .brand {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .brand-logo {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            color: var(--navy);
            box-shadow: 0 8px 32px rgba(240,165,0,0.3);
        }

        .brand h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -0.5px;
        }

        .brand h1 span { color: var(--gold); }

        .brand p {
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 300;
            margin-top: 0.4rem;
            letter-spacing: 0.5px;
        }

        /* Cartes des 3 acteurs */
        .actors {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            max-width: 320px;
        }

        .actor-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            backdrop-filter: blur(4px);
            transition: background 0.3s;
        }

        .actor-card:hover { background: rgba(255,255,255,0.09); }

        .actor-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .actor-icon.admin    { background: rgba(240,165,0,0.2); color: var(--gold); }
        .actor-icon.chef     { background: rgba(52,152,219,0.2); color: #3498DB; }
        .actor-icon.market   { background: rgba(46,204,113,0.2); color: #2ECC71; }

        .actor-info h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 2px;
        }

        .actor-info p {
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 300;
        }

        .actor-badge {
            margin-left: auto;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .actor-badge.admin  { background: rgba(240,165,0,0.15); color: var(--gold); border: 1px solid rgba(240,165,0,0.3); }
        .actor-badge.chef   { background: rgba(52,152,219,0.15); color: #3498DB; border: 1px solid rgba(52,152,219,0.3); }
        .actor-badge.market { background: rgba(46,204,113,0.15); color: #2ECC71; border: 1px solid rgba(46,204,113,0.3); }

        .panel-footer {
            position: relative;
            z-index: 1;
            margin-top: 3rem;
            color: var(--muted);
            font-size: 0.75rem;
            font-weight: 300;
            letter-spacing: 0.3px;
        }

        /* ─── PANNEAU DROIT ─── */
        .panel-right {
            width: 480px;
            min-width: 420px;
            background: var(--card-bg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
            position: relative;
        }

        .panel-right::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(180deg, transparent, var(--gold), transparent);
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header .welcome {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 0.5rem;
        }

        .form-header h2 {
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--navy);
            line-height: 1.2;
        }

        .form-header p {
            color: #8896A7;
            font-size: 0.88rem;
            font-weight: 400;
            margin-top: 0.5rem;
        }

        /* ─── ALERTE ERREUR ─── */
        .alert-error {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.1rem;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: var(--error);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── FORMULAIRE ─── */
        .form-group {
            margin-bottom: 1.4rem;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.9rem;
            transition: color 0.2s;
            pointer-events: none;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.6rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: var(--navy);
            background: #F8FAFC;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: var(--gold);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(240,165,0,0.12);
        }

        .input-wrapper input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon { color: var(--gold); }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            font-size: 0.9rem;
            padding: 4px;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: var(--navy); }

        /* ─── BOUTON ─── */
        .btn-login {
            width: 100%;
            padding: 0.95rem;
            background: linear-gradient(135deg, var(--navy-mid) 0%, var(--navy) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.2s;
            letter-spacing: 0.3px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(240,165,0,0.15), transparent);
            transition: left 0.4s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(10,22,40,0.25);
        }

        .btn-login:hover::before { left: 100%; }

        .btn-login:active { transform: translateY(0); }

        .btn-login .btn-icon { margin-right: 8px; }

        /* ─── AIDE ─── */
        .form-hint {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #EEF2F7;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #8896A7;
            font-size: 0.8rem;
        }

        .form-hint a {
            color: var(--navy-mid);
            font-weight: 600;
            text-decoration: none;
        }
        .form-hint a:hover { color: var(--gold); }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 768px) {
            html, body { overflow: auto; }
            .login-wrapper { flex-direction: column; height: auto; min-height: 100vh; }
            .panel-left { padding: 2.5rem 2rem; }
            .panel-right { width: 100%; min-width: unset; padding: 2.5rem 1.75rem; }
            .panel-right::before { display: none; }
            .actors { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- ═══ PANNEAU GAUCHE : Présentation ═══ -->
    <div class="panel-left">

        <div class="brand">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1>Edu<span>CRM</span></h1>
            <p>Plateforme de gestion des inscriptions</p>
        </div>

        <div class="actors">

            <div class="actor-card">
                <div class="actor-icon admin">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="actor-info">
                    <h4>Administrateur</h4>
                    <p>Gestion globale de la plateforme</p>
                </div>
                <span class="actor-badge admin">Admin</span>
            </div>

            <div class="actor-card">
                <div class="actor-icon chef">
                    <i class="fas fa-building-user"></i>
                </div>
                <div class="actor-info">
                    <h4>Chef de Département</h4>
                    <p>Supervision des spécialités</p>
                </div>
                <span class="actor-badge chef">Chef</span>
            </div>

            <div class="actor-card">
                <div class="actor-icon market">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="actor-info">
                    <h4>Marketiste</h4>
                    <p>Suivi des prospects et relances</p>
                </div>
                <span class="actor-badge market">Market</span>
            </div>

        </div>

        <div class="panel-footer">
            &copy; <?= date('Y') ?> EduCRM &mdash; Tous droits réservés
        </div>

    </div>

    <!-- ═══ PANNEAU DROIT : Formulaire ═══ -->
    <div class="panel-right">

        <div class="form-header">
            <div class="welcome">Bienvenue</div>
            <h2>Connectez-vous à votre espace</h2>
            <p>Entrez vos identifiants pour accéder à votre tableau de bord.</p>
        </div>

        <!-- Message d'erreur -->
        <?php if (!empty($error)): ?>
        <div class="alert-error">
            <i class="fas fa-circle-exclamation"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form action="/educrm/login" method="POST" id="loginForm" novalidate>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Adresse email</label>
                <div class="input-wrapper">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="exemple@educrm.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        autocomplete="email"
                        required
                    >
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePassword()" title="Afficher/masquer">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <i class="fas fa-right-to-bracket btn-icon"></i>
                Se connecter
            </button>

        </form>

        <div class="form-hint">
            <i class="fas fa-lock fa-xs"></i>
            <span>Problème d'accès ? Contactez votre <a href="mailto:admin@educrm.com">administrateur</a></span>
        </div>

    </div>
</div>

<script>
    // Afficher / masquer le mot de passe
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Feedback visuel à la soumission
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const btn      = document.getElementById('submitBtn');

        if (!email || !password) {
            e.preventDefault();
            return;
        }

        btn.innerHTML = '<i class="fas fa-spinner fa-spin btn-icon"></i> Connexion...';
        btn.disabled  = true;
        btn.style.opacity = '0.85';
    });
</script>

</body>
</html>
