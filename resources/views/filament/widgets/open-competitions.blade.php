@php
    use Illuminate\View\ComponentAttributeBag;

    $config = $this->getGridListConfiguration();
    $competitions = $this->getCompetitions();
    $gridColumns = $config->getGridColumns();
    $gap = $config->getGap();
@endphp

<x-filament-widgets::widget class="fi-grid-list fi-open-competitions-widget">
    <x-filament::section
        icon="heroicon-o-trophy"
        icon-color="primary"
        heading="Open Competitions"
        description="Competitions currently accepting registrations."
    >
        @if ($competitions->isEmpty())
            <div class="fi-ta-empty-state py-8">
                <div class="fi-ta-empty-state-content">
                    <div class="fi-ta-empty-state-icon-ctn">
                        <x-filament::icon icon="heroicon-o-calendar-days" class="size-6" />
                    </div>

                    <div>
                        <h4 class="fi-ta-empty-state-heading">
                            No open competitions
                        </h4>
                        <p class="fi-ta-empty-state-description">
                            Check back later for upcoming registration windows.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div
                {{
                    (new ComponentAttributeBag)
                        ->grid($gridColumns)
                        ->class(['fi-grid-list-content'])
                        ->style([
                            'gap: ' . ($gap * 0.25) . 'rem',
                        ])
                }}
            >
                @foreach ($competitions as $record)
                    @php
                        $recordUrl = $this->resolveRecordUrl($record);
                    @endphp

                    <div @class([
                        'fi-grid-list-card overflow-hidden',
                        'fi-grid-list-card-clickable' => filled($recordUrl),
                    ])>
                        @if ($recordUrl)
                            <a href="{{ $recordUrl }}" class="fi-grid-list-card-body group">
                                @include('filament.components.competition-grid-card', [
                                    'record' => $record,
                                    'recordUrl' => $recordUrl,
                                ])
                            </a>
                        @else
                            <div class="fi-grid-list-card-body">
                                @include('filament.components.competition-grid-card', [
                                    'record' => $record,
                                    'recordUrl' => null,
                                ])
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
