<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-calendar-alt me-2"></i>Calendrier du département
    </h2>
    <div>
        <a href="/educrm/chef-departement/rendezvous" class="btn btn-secondary">
            <i class="fas fa-chart-line me-2"></i>Dashboard
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locales/fr.global.min.js"></script>
<style>
    .fc-event-custom {
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 3px;
    }
    .fc-event-planifie { background-color: #ffc107; border-color: #ffc107; }
    .fc-event-confirme { background-color: #17a2b8; border-color: #17a2b8; }
    .fc-event-realise { background-color: #28a745; border-color: #28a745; }
    .fc-event-annule { background-color: #dc3545; border-color: #dc3545; }
    .fc-event-reporte { background-color: #6c757d; border-color: #6c757d; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour'
        },
        events: [
            <?php foreach ($rendezVous as $rdv): ?>
            {
                id: '<?php echo $rdv['id']; ?>',
                title: '<?php echo addslashes($rdv['prospect_nom'] ?? ''); ?> (<?php echo addslashes($rdv['marketiste_nom'] ?? ''); ?>)',
                start: '<?php echo $rdv['date_rdv'] . 'T' . $rdv['heure_rdv']; ?>',
                url: '/educrm/chef-departement/rendezvous/<?php echo $rdv['id']; ?>',
                className: 'fc-event-<?php echo strtolower($rdv['statut']); ?>',
                extendedProps: {
                    statut: '<?php echo $rdv['statut']; ?>',
                    marketiste: '<?php echo addslashes($rdv['marketiste_nom'] ?? ''); ?>'
                }
            },
            <?php endforeach; ?>
        ],
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            window.location.href = info.event.url;
        },
        eventDidMount: function(info) {
            info.el.setAttribute('title',
                info.event.title +
                '\nStatut: ' + info.event.extendedProps.statut
            );
        }
    });
    calendar.render();
});
</script>
