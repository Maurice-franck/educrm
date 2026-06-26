<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-calendar-alt me-2"></i>Rendez-vous du département
    </h2>
    <div>
        <a href="/educrm/chef-departement/rendezvous/calendar" class="btn btn-info me-2">
            <i class="fas fa-calendar me-2"></i>Calendrier
        </a>
        <a href="/educrm/chef-departement/rendezvous/all" class="btn btn-primary me-2">
            <i class="fas fa-list me-2"></i>Tous les RDV
        </a>
        <a href="/educrm/chef-departement/rendezvous/create" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Nouveau RDV
        </a>
    </div>
</div>

<!-- Statistiques du département -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total RDV</h5>
                <h2><?php echo $stats['total']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Planifiés</h5>
                <h2><?php echo $stats['planifie']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Réalisés</h5>
                <h2><?php echo $stats['realise']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">À venir</h5>
                <h2><?php echo $stats['a_venir']; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- RDV du jour -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day me-2"></i>Aujourd'hui
                </h5>
            </div>
            <div class="card-body">
                <?php
                $todayList = $todayRdv->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php if (count($todayList) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($todayList as $rdv): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo date('H:i', strtotime($rdv['heure_rdv'])); ?></strong>
                                    — <?php echo htmlspecialchars($rdv['prospect_nom'] ?? ''); ?>
                                    <br><small class="text-muted">
                                        <?php echo htmlspecialchars($rdv['prospect_telephone'] ?? ''); ?>
                                        — <i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($rdv['marketiste_nom'] ?? ''); ?>
                                    </small>
                                </div>
                                <a href="/educrm/chef-departement/rendezvous/<?php echo $rdv['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>Aucun rendez-vous aujourd'hui
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RDV à venir -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-week me-2"></i>Prochains rendez-vous
                </h5>
            </div>
            <div class="card-body">
                <?php
                $upcomingList = $upcomingRdv->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php if (count($upcomingList) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($upcomingList as $rdv): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo date('d/m/Y', strtotime($rdv['date_rdv'])); ?> à <?php echo date('H:i', strtotime($rdv['heure_rdv'])); ?></strong>
                                    <br><?php echo htmlspecialchars($rdv['prospect_nom'] ?? ''); ?>
                                    <br><small class="text-muted"><i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($rdv['marketiste_nom'] ?? ''); ?></small>
                                </div>
                                <a href="/educrm/chef-departement/rendezvous/<?php echo $rdv['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>Aucun rendez-vous à venir
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
