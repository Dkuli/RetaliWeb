<div class="relative aspect-[16/9] rounded-lg overflow-hidden">
    @if($carousel->image)
        <img src="{{ asset('storage/' . $carousel->image) }}" 
             alt="{{ $carousel->title }}"
             class="absolute inset-0 w-full h-full object-cover">
    @endif
    
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
        <div class="absolute bottom-0 w-full p-6">
            <h3 class="text-xl font-bold text-white">{{ $carousel->title }}</h3>
        </div>
    </div>
</div>