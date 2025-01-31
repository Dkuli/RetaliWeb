{{-- resources/views/filament/resources/luggage/view.blade.php --}}
<div class="space-y-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <div class="text-sm font-medium text-gray-500">Luggage Number</div>
            <div>{{ $luggage->luggage_number }}</div>
        </div>

        <div>
            <div class="text-sm font-medium text-gray-500">Pilgrim Name</div>
            <div>{{ $luggage->pilgrim_name }}</div>
        </div>

        <div>
            <div class="text-sm font-medium text-gray-500">Phone</div>
            <div>{{ $luggage->phone ?? '-' }}</div>
        </div>

        <div>
            <div class="text-sm font-medium text-gray-500">Group</div>
            <div>{{ $luggage->group }}</div>
        </div>
    </div>

    <div class="border-t pt-4">
        <div class="text-lg font-medium mb-4">Scan History</div>
        @include('filament.components.scan-history', ['scans' => $luggage->scans()->latest()->get()])
    </div>
</div>
