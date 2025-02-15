<x-filament::page>
    <div class="space-y-4">
        <div id="calendar" class="bg-white rounded-xl shadow p-4"></div>
    </div>

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: @json($events),
                    eventClick: function(info) {
                        const props = info.event.extendedProps;
                        // You can implement a modal or notification here to show event details
                    }
                });
                calendar.render();
            });
        </script>
    @endpush

    @push('styles')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    @endpush
</x-filament::page>
