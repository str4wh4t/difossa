@props(['competition'])

@php
    $isOpen = $competition->isOpenForRegistration();
    $startsAt = $competition->registration_starts_at;
    $endsAt = $competition->registration_ends_at;
    $now = now();

    $countdownValue = null;
    $countdownLabel = 'Registration window';
    $countdownTone = 'closed';

    if ($isOpen) {
        $countdownTone = 'open';

        if ($endsAt?->isFuture()) {
            $countdownValue = max(0, (int) $now->diffInDays($endsAt));
            $countdownLabel = $countdownValue === 1 ? 'day left to register' : 'days left to register';
        } else {
            $countdownLabel = 'Registration is open now';
        }
    } elseif ($startsAt?->isFuture()) {
        $countdownTone = 'upcoming';
        $countdownValue = max(0, (int) $now->diffInDays($startsAt));
        $countdownLabel = $countdownValue === 1 ? 'day until registration opens' : 'days until registration opens';
    } else {
        $countdownLabel = 'Registration is currently closed';
    }

    $progressPercent = null;

    if ($startsAt && $endsAt && $endsAt->isAfter($startsAt)) {
        $totalSeconds = $startsAt->diffInSeconds($endsAt);

        if ($totalSeconds > 0) {
            if ($now->lt($startsAt)) {
                $progressPercent = 0;
            } elseif ($now->gte($endsAt)) {
                $progressPercent = 100;
            } else {
                $progressPercent = min(100, max(0, ($startsAt->diffInSeconds($now) / $totalSeconds) * 100));
            }
        }
    }

    $formatDate = fn ($date): ?string => $date?->format('F j, Y · g:i A');
@endphp

<div {{ $attributes->merge(['class' => 'competition-registration-card']) }}>
    <div class="competition-registration-card-glow" aria-hidden="true"></div>

    <div class="relative">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-skilline-orange">
                    Registration Window
                </p>
                <p class="mt-1 text-base text-gray-600">
                    @if ($isOpen)
                        Open for participants
                    @elseif ($startsAt?->isFuture())
                        Opening soon
                    @else
                        Not accepting registrations
                    @endif
                </p>
            </div>

            <span @class([
                'competition-registration-status',
                'competition-registration-status--open' => $countdownTone === 'open',
                'competition-registration-status--upcoming' => $countdownTone === 'upcoming',
                'competition-registration-status--closed' => $countdownTone === 'closed',
            ])>
                {{ $competition->status?->name ?? 'Competition' }}
            </span>
        </div>

        @if ($countdownValue !== null)
            <div class="competition-registration-countdown">
                <p class="competition-registration-countdown-value">{{ $countdownValue }}</p>
                <p class="competition-registration-countdown-label">{{ $countdownLabel }}</p>
            </div>
        @else
            <div class="competition-registration-countdown competition-registration-countdown--compact">
                <p class="competition-registration-countdown-label">{{ $countdownLabel }}</p>
            </div>
        @endif

        <div class="competition-registration-dates">
            <div class="competition-registration-date">
                <div class="competition-registration-date-icon competition-registration-date-icon--open">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="competition-registration-date-label">Registration opens</p>
                    <p class="competition-registration-date-value">
                        {{ $startsAt ? $formatDate($startsAt) : 'No start limit' }}
                    </p>
                </div>
            </div>

            <div class="competition-registration-date">
                <div class="competition-registration-date-icon competition-registration-date-icon--close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="competition-registration-date-label">Registration closes</p>
                    <p class="competition-registration-date-value">
                        {{ $endsAt ? $formatDate($endsAt) : 'No end limit' }}
                    </p>
                </div>
            </div>
        </div>

        @if ($progressPercent !== null)
            <div class="competition-registration-progress">
                <div class="competition-registration-progress-track">
                    <div
                        class="competition-registration-progress-bar"
                        style="width: {{ number_format($progressPercent, 2, '.', '') }}%"
                    ></div>
                </div>
                <p class="competition-registration-progress-label">
                    {{ number_format($progressPercent, 0) }}% of registration period elapsed
                </p>
            </div>
        @endif
    </div>
</div>
