<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">FCM Status</h2>
                <p class="text-sm text-gray-500">Firebase Cloud Messaging</p>
            </div>

            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $fcmStatus === 'Connected' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $fcmStatus }}
                </span>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold">{{ $lastDeliveryStats['sent'] }}</p>
                <p class="text-sm text-gray-500">Notifications Sent</p>
            </div>

            <div class="text-center">
                <p class="text-2xl font-bold">{{ $lastDeliveryStats['delivered'] }}</p>
                <p class="text-sm text-gray-500">Successfully Delivered</p>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
