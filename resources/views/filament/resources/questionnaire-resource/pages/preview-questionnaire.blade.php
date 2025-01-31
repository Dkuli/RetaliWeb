{{-- resources/views/filament/resources/questionnaire-resource/pages/preview.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold">{{ $record->title }}</h1>
            <p class="mt-2 text-gray-600">{{ $record->description }}</p>
            <div class="mt-4 flex gap-4 text-sm text-gray-500">
                <span>Periode: {{ $record->start_date->translatedFormat('d F Y') }} - {{ $record->end_date->translatedFormat('d F Y') }}</span>
                <span>Status:
                    <x-filament::badge
                    :color="match ($record->status) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'closed' => 'danger',
                    }"
                    class="text-sm">
                    {{ ucfirst($record->status) }}
                </x-filament::badge> </div>
        </div>

        @foreach($record->questions()->orderBy('order')->get() as $index => $question)
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-medium">{{ $question->question_text }}</h3>
                            @if($question->is_required)
                                <span class="text-xs text-red-500">*Wajib diisi</span>
                            @endif
                        </div>

                        <div class="mt-4 space-y-2">
                            @switch($question->type)
                                @case('multiple_choice')
                                    @foreach($question->options as $option)
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" disabled class="text-primary-600">
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                    @break

                                @case('text')
                                    <input type="text" disabled
                                           class="w-full border-gray-300 rounded-lg"
                                           placeholder="Jawaban teks">
                                    @break

                                @case('rating')
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button disabled class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                {{ $i }}
                                            </button>
                                        @endfor
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
