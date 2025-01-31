{{-- resources/views/filament/components/scan-history.blade.php --}}
<div class="space-y-6">
    @forelse ($scans as $scan)
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-lg font-semibold">{{ $scan->tourLeader->name }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ $scan->scanned_at->format('M d, Y H:i') }}
                        ({{ $scan->scanned_at->diffForHumans() }})
                    </p>
                </div>
                <div class="text-sm text-gray-500">
                    <p>Lat: {{ number_format($scan->latitude, 6) }}</p>
                    <p>Long: {{ number_format($scan->longitude, 6) }}</p>
                    <p id="address-{{ $loop->index }}">Loading address...</p>
                </div>
            </div>
            <div class="border-t pt-4">
                <p class="text-sm text-gray-700">Additional scan details can go here.</p>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat={{ $scan->latitude }}&lon={{ $scan->longitude }}&zoom=18&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('address-{{ $loop->index }}').innerText = data.display_name;
                    })
                    .catch(error => {
                        document.getElementById('address-{{ $loop->index }}').innerText = 'Address not found';
                    });
            });
        </script>
    @empty
        <div class="text-gray-500 text-center py-4">
            No scan history available
        </div>
    @endforelse
</div>
