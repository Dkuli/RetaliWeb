{{-- resources/views/filament/resources/content/pages/gallery-view.blade.php --}}
<x-filament::page>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($contents as $content)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="aspect-w-16 aspect-h-9">
                    @if($content->type === 'photo')
                        <img
                            src="{{ $content->getFirstMediaUrl('media') }}"
                            alt="{{ $content->title }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <video
                            controls
                            class="w-full h-full object-cover"
                        >
                            <source src="{{ $content->getFirstMediaUrl('media') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $content->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $content->group->name }}</p>
                    @if($content->tourLeader)
                        <p class="text-sm text-gray-500 dark:text-gray-400">By {{ $content->tourLeader->name }}</p>
                    @endif

                    <div class="mt-3 flex justify-between items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $content->type === 'photo' ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' : 'bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900' }}">
                            {{ ucfirst($content->type) }}
                        </span>

                        <a
                            href="{{ $content->getFirstMediaUrl('media') }}"
                            download
                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                        >
                            <x-heroicon-o-download class="w-5 h-5"/>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $contents->links() }}
    </div>
</x-filament::page>
