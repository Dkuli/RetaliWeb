<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium">Upcoming Departures</h2>
        <div class="mt-4 space-y-4">
            @foreach($this->getUpcomingGroups() as $group)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="font-medium">{{ $group->name }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ $group->departure_date->format('M d, Y') }}
                            ({{ $group->pilgrims_count }} pilgrims)
                        </p>
                    </div>
                    <x-filament::button
                        size="sm"
                        :href="route('filament.resources.groups.view', $group)"
                    >
                        View Details
                    </x-filament::button>
                </div>
            @endforeach
        </div>
    </x-filament::card>
</x-filament::widget>
