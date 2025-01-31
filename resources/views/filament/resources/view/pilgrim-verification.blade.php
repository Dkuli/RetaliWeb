{{-- resources/views/pilgrim-verification.blade.php --}}
<x-app-layout>
    <div class="max-w-2xl mx-auto p-4">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center space-x-4">
                <img src="{{ $pilgrim->getFirstMediaUrl('photo') }}"
                     class="w-24 h-24 rounded-full">
                <div>
                    <h2 class="text-2xl font-bold">{{ $pilgrim->name }}</h2>
                    <p class="text-gray-600">ID: {{ str_pad($pilgrim->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="border-t pt-4">
                    <h3 class="font-semibold">Current Group</h3>
                    <p>{{ $currentGroup?->name ?? 'Not assigned' }}</p>
                </div>

                @if($healthInfo)
                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-yellow-600">
                            Requires Medical Attention
                        </h3>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
