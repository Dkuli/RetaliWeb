{{-- resources/views/filament/tables/columns/media-preview.blade.php --}}
<div class="w-32 h-32">
    @if($getRecord()->type === 'photo')
        <img
            src="{{ $getRecord()->getFirstMediaUrl('photos', 'thumbnail') }}"
            alt="{{ $getRecord()->title }}"
            class="w-full h-full rounded object-cover"
        >
    @elseif($getRecord()->type === 'video')
        <div class="relative w-full h-full">
            <video
                class="w-full h-full rounded object-cover"
                poster="{{ $getRecord()->getFirstMediaUrl('videos', 'thumbnail') }}"
            >
                <source src="{{ $getRecord()->getFirstMediaUrl('videos') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="w-12 h-12 rounded-full bg-black bg-opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    </svg>
                </span>
            </div>
        </div>
    @endif
</div>
