<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Marketiste - EDUCRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ── MODE SOMBRE ── */
        body.dark-mode {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }
        body.dark-mode .content {
            background-color: #16213e;
            color: #e0e0e0;
        }
        body.dark-mode .card {
            background-color: #0f3460;
            color: #e0e0e0;
            border: 1px solid #444;
        }
        body.dark-mode .card-header {
            background-color: #0f3460 !important;
            color: #e0e0e0 !important;
            border-bottom: 1px solid #444;
        }
        body.dark-mode .table {
            color: #e0e0e0;
        }
        body.dark-mode .table-light,
        body.dark-mode .table-dark {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }
        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(255,255,255,0.05);
        }
        body.dark-mode .table-hover > tbody > tr:hover {
            background-color: rgba(255,255,255,0.1);
        }
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #1a1a2e;
            color: #e0e0e0;
            border-color: #555;
        }
        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }
        body.dark-mode .form-control.bg-light {
            background-color: #0f3460 !important;
            color: #aaa !important;
        }
        body.dark-mode .modal-content {
            background-color: #0f3460;
            color: #e0e0e0;
        }
        body.dark-mode .modal-header {
            border-bottom: 1px solid #444;
        }
        body.dark-mode .modal-footer {
            border-top: 1px solid #444;
        }
        body.dark-mode .alert-info {
            background-color: #1a3a5c;
            color: #90caf9;
            border-color: #1e88e5;
        }
        body.dark-mode .alert-light {
            background-color: #1a1a2e;
            color: #e0e0e0;
            border-color: #444;
        }
        body.dark-mode .nav-tabs .nav-link {
            color: #aaa;
        }
        body.dark-mode .nav-tabs .nav-link.active {
            background-color: #0f3460;
            color: #fff;
            border-color: #444 #444 #0f3460;
        }
        body.dark-mode .breadcrumb {
            background-color: transparent;
        }
        body.dark-mode .input-group .btn-outline-secondary {
            color: #aaa;
            border-color: #555;
        }

        /* Bouton toggle */
        .btn-dark-mode {
            background: none;
            border: none;
            color: white;
            padding: 12px 20px;
            display: block;
            width: 100%;
            text-align: left;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
            margin: 5px 10px;
            width: calc(100% - 20px);
        }
        .btn-dark-mode:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: 0.3s;
            border-radius: 5px;
            margin: 5px 10px;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar .user-box {
            margin: 5px 10px 15px;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
        }

        .sidebar .user-box small {
            opacity: 0.8;
        }

        .content {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="p-4">
                    <h3 class="text-white text-center">EDUCRM</h3>
                    <p class="text-white text-center mb-0" style="opacity:.8; font-size:.8rem;">Espace Marketiste</p>
                    <hr class="bg-light">
                </div>

                <div class="user-box">
                    <i class="fas fa-user-circle me-2"></i>
                    <strong><?php echo htmlspecialchars(($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? '')); ?></strong>
                    <br>
                    <small>Marketiste</small>
                </div>

                <nav>
                    <?php $currentUri = $_SERVER['REQUEST_URI'] ?? ''; ?>
                    <a href="/educrm/marketiste/supervision" class="<?php echo (strpos($currentUri, '/marketiste/supervision') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i> Supervision
                    </a>
                    <a href="/educrm/marketiste/rendezvous" class="<?php echo (strpos($currentUri, '/marketiste/rendezvous') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt"></i> Rendez-vous
                    </a>
                    <a href="/educrm/marketiste/prospects" class="<?php echo (strpos($currentUri, '/marketiste/prospects') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-user-friends"></i> Prospects
                    </a>
                    <button class="btn-dark-mode" onclick="toggleDarkMode()" id="btnDarkMode">
                    <i class="fas fa-moon" id="iconDarkMode"></i>
                    <span id="labelDarkMode">Mode sombre</span>
                </button>
                    <form method="POST" action="/educrm/logout" style="margin: 5px 10px;">
                        <button type="submit" style="
                            background: none;
                            border: none;
                            color: white;
                            padding: 12px 20px;
                            display: block;
                            width: 100%;
                            text-align: left;
                            cursor: pointer;
                            border-radius: 5px;
                            transition: 0.3s;
                        " onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'"
                           onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php include($content); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Appliquer le mode sauvegardé au chargement
    (function() {
        if (localStorage.getItem('darkMode') === 'on') {
            document.body.classList.add('dark-mode');
            document.getElementById('iconDarkMode').className  = 'fas fa-sun';
            document.getElementById('labelDarkMode').textContent = 'Mode clair';
        }
    })();

    function toggleDarkMode() {
        const body    = document.body;
        const icon    = document.getElementById('iconDarkMode');
        const label   = document.getElementById('labelDarkMode');

        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode');
            icon.className   = 'fas fa-moon';
            label.textContent = 'Mode sombre';
            localStorage.setItem('darkMode', 'off');
        } else {
            body.classList.add('dark-mode');
            icon.className   = 'fas fa-sun';
            label.textContent = 'Mode clair';
            localStorage.setItem('darkMode', 'on');
        }
    }
</script>
</body>

</html>
