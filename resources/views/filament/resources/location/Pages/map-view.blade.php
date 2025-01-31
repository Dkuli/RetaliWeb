{{-- resources/views/filament/resources/tour-leader-location/pages/map.blade.php --}}
<x-filament::page>
    <div class="p-4 space-y-4">
        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-filament::select
                        wire:model="dateRange"
                        :options="[
                            'today' => 'Today',
                            'yesterday' => 'Yesterday',
                            'week' => 'Last 7 Days',
                            'month' => 'Last 30 Days',
                        ]"
                        label="Date Range"
                    />
                </div>
                <div>
                    <x-filament::select
                        wire:model="selectedTourLeaders"
                        :options="$tourLeaders"
                        label="Tour Leaders"
                        multiple
                        placeholder="All Tour Leaders"
                    />
                </div>
            </div>
        </div>

        {{-- Map Container --}}
        <div class="bg-white rounded-lg shadow">
            <div id="map" style="height: 700px;"></div>
        </div>

        {{-- Legend --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium mb-2">Tour Leaders</h3>
            <div id="legend" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                {{-- Will be populated by JavaScript --}}
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

        <script>
            let map;
            let markers = [];
            let paths = [];

            function initMap() {
                if (map) {
                    map.remove();
                }

                map = L.map('map').setView([0, 0], 2);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
            }

            function updateMap(locations) {
                initMap();
                clearMapData();

                const colors = [
                    '#FF0000', '#00FF00', '#0000FF', '#FF00FF',
                    '#00FFFF', '#FFA500', '#800080', '#008000'
                ];

                // Clear legend
                const legend = document.getElementById('legend');
                legend.innerHTML = '';

                locations.forEach((tourLeader, index) => {
                    const color = colors[index % colors.length];

                    // Add to legend
                    const legendItem = document.createElement('div');
                    legendItem.className = 'flex items-center space-x-2';
                    legendItem.innerHTML = `
                        <span class="inline-block w-4 h-4 rounded-full" style="background-color: ${color}"></span>
                        <span>${tourLeader.tour_leader}</span>
                    `;
                    legend.appendChild(legendItem);

                    if (tourLeader.locations.length === 0) return;

                    // Create path
                    const coordinates = tourLeader.locations.map(loc => [loc.lat, loc.lng]);
                    const path = L.polyline(coordinates, {
                        color: color,
                        weight: 3,
                        opacity: 0.8
                    }).addTo(map);
                    paths.push(path);

                    // Add markers
                    tourLeader.locations.forEach((location, i) => {
                        const marker = L.circleMarker([location.lat, location.lng], {
                            radius: 8,
                            fillColor: color,
                            color: '#fff',
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(map);

                        const popupContent = `
                            <div class="p-2">
                                <h3 class="font-bold">${tourLeader.tour_leader}</h3>
                                <p>Time: ${location.timestamp}</p>
                                <p>Lat: ${location.lat}</p>
                                <p>Lng: ${location.lng}</p>
                                ${location.speed ? `<p>Speed: ${location.speed} m/s</p>` : ''}
                                ${location.heading ? `<p>Heading: ${location.heading}°</p>` : ''}
                                ${location.accuracy ? `<p>Accuracy: ${location.accuracy}m</p>` : ''}
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        markers.push(marker);

                        // If this is the most recent location, open the popup
                        if (i === 0) {
                            marker.openPopup();
                        }
                    });
                });

                // Fit bounds if we have locations
                if (markers.length > 0) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds(), { padding: [50, 50] });
                }
            }

            function clearMapData() {
                markers.forEach(marker => marker.remove());
                paths.forEach(path => path.remove());
                markers = [];
                paths = [];
            }

            // Initial load
            document.addEventListener('DOMContentLoaded', function() {
                updateMap(@json($locations));
            });

            // Listen for Livewire updates
            Livewire.on('locationDataUpdated', function(data) {
                updateMap(data.locations);
            });
        </script>
    @endpush
</x-filament::page>
