<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- Tour Leader List -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-medium mb-4">Active Tour Leaders</h3>
                <div class="space-y-2">
                    @foreach($tourLeaders as $leader)
                        <div
                            class="p-3 rounded-lg cursor-pointer hover:bg-gray-50 {{ $selectedLeader === $leader['id'] ? 'bg-primary-50' : '' }}"
                            wire:click="$set('selectedLeader', {{ $leader['id'] }})"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ $leader['avatar_url'] }}" class="h-10 w-10 rounded-full">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $leader['name'] }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $leader['group'] ?? 'No Group' }}
                                    </p>
                                    @if($leader['last_location'])
                                        <p class="text-xs text-gray-400">
                                            Last seen: {{ $leader['last_location']['tracked_at'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow p-4">
                <div id="map" style="height: 500px;" wire:ignore></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize the map
                var map = L.map('map', {
                    worldCopyJump: true,
                    maxBounds: [
                        [-90, -180], // South-West bounds
                        [90, 180]    // North-East bounds
                    ],
                    maxBoundsViscosity: 1.0,
                    minZoom: 2,
                    maxZoom: 18
                }).setView([0, 0], 2);

                var markers = {};

                // Add a tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Add markers for each tour leader
                @foreach ($tourLeaders as $leader)
                    @if ($leader['last_location'])
                        markers[{{ $leader['id'] }}] = L.marker([
                            {{ $leader['last_location']['latitude'] }},
                            {{ $leader['last_location']['longitude'] }}
                        ]).addTo(map)
                        .bindPopup('<b>{{ $leader['name'] }}</b><br>{{ $leader['last_location']['address'] }}');
                    @endif
                @endforeach

                // Initialize Pusher
                const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                    encrypted: true
                });

                // Subscribe to the tracking channel
                const channel = pusher.subscribe('tracking');

                // Listen for location updates
                channel.bind('location-updated', function(data) {
                    const location = data.location;

                    // Update or create marker
                    if (markers[location.tour_leader_id]) {
                        markers[location.tour_leader_id].setLatLng([
                            location.latitude,
                            location.longitude
                        ]);
                    } else {
                        markers[location.tour_leader_id] = L.marker([
                            location.latitude,
                            location.longitude
                        ]).addTo(map);
                    }

                    // Trigger Livewire event
                    @this.call('handleLocationUpdate', data);
                });
            });
        </script>
    @endpush
</x-filament-panels::page>
