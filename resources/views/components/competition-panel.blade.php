@props(['competition'])

@php
    use Illuminate\Support\Str;

    $isOpen = $competition->isOpenForRegistration();

    $titleWords = preg_split('/\s+/', trim($competition->title)) ?: [];
    $highlight = count($titleWords) > 1 ? array_pop($titleWords) : null;
    $lead = $highlight ? implode(' ', $titleWords) : $competition->title;

    $registrationLabel = match (true) {
        $isOpen && $competition->registration_ends_at => 'Registration open until ' . $competition->registration_ends_at->format('F j, Y') . '.',
        $isOpen => 'Registration is currently open.',
        $competition->registration_ends_at?->isPast() => 'Registration closed on ' . $competition->registration_ends_at->format('F j, Y') . '.',
        $competition->registration_starts_at?->isFuture() => 'Registration opens on ' . $competition->registration_starts_at->format('F j, Y') . '.',
        default => null,
    };

    $bannerUrl = $competition->banner_image_url ?? $competition->banner_thumbnail_url;
    $detailUrl = route('competitions.show', $competition);
    $descriptionPreview = filled($competition->description)
        ? Str::limit(trim(strip_tags($competition->description)), 220)
        : null;
@endphp

<article {{ $attributes }}>
    <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="order-2 flex flex-col items-center text-center lg:order-1 lg:items-start lg:text-left">
                <x-competition-status-badge :status="$competition->status" />

                <h2 class="relative mt-4 max-w-xl text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">
                    <span class="relative text-darken">{{ $lead }}</span>@if ($highlight)<span class="relative text-skilline-orange"> {{ $highlight }}</span>@endif
                </h2>

                @if ($descriptionPreview)
                    <p class="mt-6 max-w-lg text-lg leading-relaxed text-gray-600">
                        {{ $descriptionPreview }}
                    </p>
                @endif

                @if ($registrationLabel)
                    <p class="mt-4 max-w-lg text-base leading-relaxed text-gray-500">
                        {{ $registrationLabel }}
                    </p>
                @endif

                <a
                    href="{{ $detailUrl }}"
                    wire:navigate
                    class="link-accent mt-8 text-lg underline decoration-darken/30 underline-offset-8 transition-colors hover:decoration-skilline-orange"
                >
                    Learn more
                </a>
            </div>

            <div class="relative order-1 mx-auto w-full max-w-xl lg:order-2 lg:max-w-none">
                <div
                    class="absolute -left-3 -top-3 z-0 h-20 w-20 rounded-tl-2xl border-l-[6px] border-t-[6px] border-skilline-cyan sm:-left-4 sm:-top-4 sm:h-24 sm:w-24 lg:h-28 lg:w-28"
                    aria-hidden="true"
                ></div>

                <div
                    class="absolute -bottom-4 -right-3 z-0 h-20 w-28 rounded-lg bg-skilline-orange sm:-bottom-5 sm:-right-4 sm:h-24 sm:w-36"
                    aria-hidden="true"
                ></div>

                @if ($bannerUrl)
                    <a href="{{ $detailUrl }}" wire:navigate class="relative z-10 block overflow-hidden rounded-2xl bg-white shadow-xl">
                        <x-competition-status-badge
                            :status="$competition->status"
                            class="absolute left-4 top-4 z-20 shadow-md"
                        />

                        <img
                            src="{{ $bannerUrl }}"
                            alt="{{ $competition->title }}"
                            class="aspect-[4/3] w-full object-cover transition-transform duration-500 hover:scale-105 sm:aspect-video"
                            loading="lazy"
                        >
                    </a>
                @else
                    <a href="{{ $detailUrl }}" wire:navigate class="relative z-10 flex aspect-video items-center justify-center rounded-2xl bg-gray-100 text-sm text-gray-500 shadow-xl">
                        <x-competition-status-badge
                            :status="$competition->status"
                            class="absolute left-4 top-4 z-20 shadow-md"
                        />

                        No banner image
                    </a>
                @endif
            </div>
        </div>
</article>
