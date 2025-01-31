{{-- resources/views/filament/resources/group/pages/calendar.blade.php --}}
<x-filament::page>
    @vite(['resources/js/calendar.js'])
    
    <div class="bg-white rounded-lg shadow p-4">
        <div class="mb-4">
            <h2 class="text-lg font-medium">Schedule Calendar for {{ $record->name }}</h2>
            <p class="text-sm text-gray-500">
                {{ $record->departure_date->format('d M Y') }} - {{ $record->return_date->format('d M Y') }}
            </p>
        </div>

        <div id="calendar"></div>

        <div class="mt-4 flex gap-4">
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-prayer mr-2"></span> Prayer</span>
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-transport mr-2"></span> Transport</span>
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-meal mr-2"></span> Meal</span>
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-tour mr-2"></span> Tour</span>
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-rest mr-2"></span> Rest</span>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.initCalendar('calendar', @json($events));
            });
        </script>

        <style>
            .fc-event {
                border: none !important;
                padding: 2px 4px;
                border-radius: 4px;
            }
            .fc-event.bg-prayer { background-color: #818cf8 !important; color: white; }
            .fc-event.bg-transport { background-color: #f87171 !important; color: white; }
            .fc-event.bg-meal { background-color: #34d399 !important; color: white; }
            .fc-event.bg-tour { background-color: #fbbf24 !important; color: white; }
            .fc-event.bg-rest { background-color: #9ca3af !important; color: white; }
            .fc-event.bg-default { background-color: #e5e7eb !important; color: #374151; }
        </style>
    </div>
</x-filament::page>