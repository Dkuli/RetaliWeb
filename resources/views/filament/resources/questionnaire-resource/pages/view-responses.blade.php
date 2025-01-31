// resources/views/filament/resources/questionnaire-resource/pages/view-responses.blade.php
<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Total Responses</h3>
                        <p class="mt-1 text-3xl font-semibold text-primary-600">
                            {{ $this->getViewData()['summary']['total_responses'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-primary-100 rounded-full">
                        <x-heroicon-o-document-text class="w-6 h-6 text-primary-600" />
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Completion Rate</h3>
                        <p class="mt-1 text-3xl font-semibold text-success-600">
                            {{ $this->getViewData()['summary']['completion_rate'] }}%
                        </p>
                    </div>
                    <div class="p-3 bg-success-100 rounded-full">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-success-600" />
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Time Remaining</h3>
                        <p class="mt-1 text-3xl font-semibold text-warning-600">
                            {{ $record->end_date->diffForHumans() }}
                        </p>
                    </div>
                    <div class="p-3 bg-warning-100 rounded-full">
                        <x-heroicon-o-clock class="w-6 h-6 text-warning-600" />
                    </div>
                </div>
            </x-filament::card>
        </div>

        <!-- Question Analysis -->
        <x-filament::card>
            <div x-data="{ activeTab: @entangle('activeTab') }">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button
                            @click="activeTab = 'summary'"
                            :class="{'border-primary-500 text-primary-600': activeTab === 'summary',
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'summary'}"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Summary
                        </button>
                        <button
                            @click="activeTab = 'individual'"
                            :class="{'border-primary-500 text-primary-600': activeTab === 'individual',
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'individual'}"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Individual Responses
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="mt-6">
                    <div x-show="activeTab === 'summary'">
                        @foreach($this->getViewData()['summary']['question_summaries'] as $summary)
                            <div class="mb-8">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">
                                    {{ $summary['question'] }}
                                </h4>

                                @if($summary['type'] === 'multiple_choice')
                                    <div class="h-64">
                                        <canvas x-data="{
                                            chart: null,
                                            init() {
                                                this.chart = new Chart(this.$el, {
                                                    type: 'bar',
                                                    data: {{ json_encode($summary['chart_data']) }},
                                                    options: {
                                                        responsive: true,
                                                        maintainAspectRatio: false,
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true,
                                                                ticks: {
                                                                    stepSize: 1
                                                                }
                                                            }
                                                        }
                                                    }
                                                })
                                            }
                                        }"></canvas>
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        @foreach($summary['recent_answers'] as $answer)
                                            <div class="p-3 bg-gray-50 rounded-lg">
                                                {{ $answer }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div x-show="activeTab === 'individual'">
                        <div class="flex space-x-4 mb-6">
                            <x-filament::select
                                wire:model="selectedTourLeader"
                                :options="$this->getViewData()['tourLeaders']"
                                placeholder="Select Tour Leader"
                            />
                        </div>

                        @if($selectedTourLeader)
                            <div class="space-y-6">
                                @foreach($this->getViewData()['responses'] as $response)
                                    <x-filament::card>
                                        <div class="space-y-4">
                                            @foreach($response['answers'] as $answer)
                                                <div>
                                                    <h5 class="font-medium text-gray-900">
                                                        {{ $answer['question'] }}
                                                    </h5>
                                                    <p class="mt-1 text-gray-600">
                                                        {{ $answer['answer'] }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </x-filament::card>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-filament::card>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</x-filament-panels::page>
