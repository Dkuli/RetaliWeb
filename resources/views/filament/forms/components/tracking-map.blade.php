<div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div id="tracking-detail-map" class="h-[400px] w-full rounded-xl"></div>
</div>

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initDetailMap();
        });

        function initDetailMap() {
            if (!document.getElementById('tracking-detail-map')) {
                console.error('Detail map container not found');
                return;
            }

            try {
                const lat = {{ number_format($getRecord()->latitude, 8) }};
                const lng = {{ number_format($getRecord()->longitude, 8) }};

                // Initialize map
                const detailMap = L.map('tracking-detail-map').setView([lat, lng], 15);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(detailMap);

                // Add marker
                const content = `
                    <div class="tracking-popup">
                        <strong>Location Details</strong><br>
                        ${@json($getRecord()->address)}<br>
                        Time: ${@json($getRecord()->tracked_at->format('Y-m-d H:i:s'))}
                    </div>
                `;

                L.marker([lat, lng])
                    .bindPopup(content)
                    .addTo(detailMap)
                    .openPopup();
            } catch (error) {
                console.error('Failed to initialize detail map:', error);
            }
        }
    </script>
@endpush
