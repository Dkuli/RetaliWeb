<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit" class="w-full">
                Kirim Notifikasi
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
