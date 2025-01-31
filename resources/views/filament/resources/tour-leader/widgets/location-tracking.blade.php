<x-filament::widget>
    <div
        x-data="{ locations: @js($this->getLocations()) }"
        x-init="
            map = L.map($refs.map).setView([0, 0], 2);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            function updateMarkers() {
                locations.forEach(location => {
                    L.marker([location.latitude, location.longitude])
                        .bindPopup(location.name + '<br>' + location.group + '<br>Last update: ' + location.lastUpdate)
                        .addTo(map);
                });
            }

            updateMarkers();

            $wire.on('refreshMap', () => {
                locations = $wire.getLocations();
                updateMarkers();
            });
        "
    >
        <div x-ref="map" class="h-[400px] rounded-lg"></div>
    </div>
</x-filament::widget>
