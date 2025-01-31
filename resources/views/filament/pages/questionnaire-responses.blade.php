<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-filament::card>
                <div class="flex items-center justify-between p-6">
                    <div>
                        <h3 class="text-lg font-medium">Total Responses</h3>
                        <p class="text-3xl font-bold text-primary-600">
                            {{ $this->summary['total'] }}
                        </p>
                    </div>
                    <x-heroicon-o-document-text class="w-12 h-12 text-gray-300" />
                </div>
            </x-filament::card>

            <!-- Tambahkan card lainnya -->
        </div>

        <!-- Tabs -->
        <x-filament::card>
            <div x-data="{ activeTab: 'summary' }" class="space-y-6">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8">
                        <button @click="activeTab = 'summary'"
                            :class="activeTab === 'summary' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            Summary
                        </button>
                        <button @click="activeTab = 'individual'"
                            :class="activeTab === 'individual' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            Individual Responses
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div x-show="activeTab === 'summary'" class="space-y-8">
                    @foreach($this->summary['questions'] as $question)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold mb-4">{{ $question['text'] }}</h4>

                        @if($question['type'] === 'multiple_choice')
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($question['answers'] as $option => $count)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <span>{{ $option }}</span>
                                    <span class="font-semibold">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach($question['answers'] as $answer)
                                <div class="p-3 bg-gray-50 rounded">
                                    {{ $answer }}
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Individual Responses -->
                <div x-show="activeTab === 'individual'" class="space-y-6">
                    <x-filament::input.select wire:model="selectedTourLeader" label="Select Tour Leader">
                        <option value="">All Tour Leaders</option>
                        @foreach($this->tourLeaders as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </x-filament::input.select>

                    @foreach($this->responses as $response)
                    <x-filament::card>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <h5 class="font-semibold">{{ $response->tourLeader->name }}</h5>
                                <span class="text-sm text-gray-500">
                                    {{ $response->submitted_at->format('d M Y H:i') }}
                                </span>
                            </div>

                            @foreach($response->answers as $answer)
                            <div class="border-t pt-4">
                                <p class="font-medium">{{ $answer->question->question_text }}</p>
                                <p class="text-gray-600 mt-1">
                                    {{ $answer->answer_text ?? implode(', ', $answer->selected_options ?? []) }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </x-filament::card>
                    @endforeach
                </div>
            </div>
        </x-filament::card>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</x-filament-panels::page>
