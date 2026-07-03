@php
    use App\Models\Competition;

    /** @var Competition $record */
    $bannerUrl = $record->banner_image_url ?? $record->banner_thumbnail_url;
    $startsAt = $record->registration_starts_at?->format('M j, Y · g:i A') ?? 'No start limit';
    $endsAt = $record->registration_ends_at?->format('M j, Y · g:i A') ?? 'No end limit';
    $daysLeft = $record->registration_ends_at?->isFuture()
        ? max(0, (int) now()->diffInDays($record->registration_ends_at))
        : null;
@endphp

<div class="fi-competition-card">
    <div class="fi-competition-card-banner">
        @if ($bannerUrl)
            <img
                src="{{ $bannerUrl }}"
                alt="{{ $record->title }}"
                loading="lazy"
            >
        @else
            <div class="fi-competition-card-placeholder">
                <x-filament::icon
                    icon="heroicon-o-photo"
                    class="size-10 text-gray-400 dark:text-gray-500"
                />
            </div>
        @endif

        <div class="fi-competition-card-banner-overlay"></div>

        <div class="fi-competition-card-banner-badges">
            <x-filament::badge color="success">
                {{ $record->status?->name ?? 'Open' }}
            </x-filament::badge>

            @if ($daysLeft !== null)
                <x-filament::badge color="warning">
                    @if ($daysLeft === 0)
                        Closes today
                    @elseif ($daysLeft === 1)
                        1 day left
                    @else
                        {{ $daysLeft }} days left
                    @endif
                </x-filament::badge>
            @endif
        </div>

        <h4 class="fi-competition-card-banner-title">
            {{ $record->title }}
        </h4>
    </div>

    <div class="fi-competition-card-body">
        <div class="fi-competition-card-date">
            <x-filament::icon
                icon="heroicon-o-arrow-right-start-on-rectangle"
                class="fi-competition-card-date-icon fi-competition-card-date-icon--open"
            />
            <div>
                <p class="fi-competition-card-date-label">Registration opens</p>
                <p class="fi-competition-card-date-value">{{ $startsAt }}</p>
            </div>
        </div>

        <div class="fi-competition-card-date">
            <x-filament::icon
                icon="heroicon-o-arrow-right-end-on-rectangle"
                class="fi-competition-card-date-icon fi-competition-card-date-icon--close"
            />
            <div>
                <p class="fi-competition-card-date-label">Registration closes</p>
                <p class="fi-competition-card-date-value">{{ $endsAt }}</p>
            </div>
        </div>

        @if (filled($recordUrl ?? null))
            <div class="fi-competition-card-cta">
                <span>{{ $record->registration_ends_at?->isFuture() ? 'Register now' : 'View details' }}</span>
                <x-filament::icon icon="heroicon-m-arrow-right" class="fi-competition-card-cta-icon" />
            </div>
        @endif
    </div>
</div>
