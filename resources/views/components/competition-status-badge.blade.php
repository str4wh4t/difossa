@props(['status'])

@php
    $tone = match ($status?->slug) {
        \App\Models\CompetitionStatus::SLUG_OPEN => 'open',
        \App\Models\CompetitionStatus::SLUG_CLOSED => 'closed',
        \App\Models\CompetitionStatus::SLUG_FINISHED => 'finished',
        default => 'default',
    };
@endphp

@if ($status)
    <span {{ $attributes->class([
        'competition-status-badge',
        'competition-status-badge--' . $tone,
    ]) }}>
        {{ $status->name }}
    </span>
@endif
