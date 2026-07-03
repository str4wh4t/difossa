@props(['competitions'])

<aside class="content-panel lg:sticky lg:top-24 lg:self-start">
    <h2 class="border-b border-gray-200 pb-3 text-lg font-bold text-darken">
        Other Competitions
    </h2>

    @if ($competitions->isEmpty())
        <p class="mt-4 text-sm text-gray-500">No other competitions yet.</p>
    @else
        <ul class="mt-4 space-y-4">
            @foreach ($competitions as $item)
                @php
                    $thumbnailUrl = $item->banner_thumbnail_url ?? $item->banner_image_url;
                @endphp

                <li>
                    <a
                        href="{{ route('competitions.show', $item) }}"
                        wire:navigate
                        class="group flex gap-3"
                    >
                        @if ($thumbnailUrl)
                            <img
                                src="{{ $thumbnailUrl }}"
                                alt="{{ $item->title }}"
                                class="h-16 w-24 shrink-0 rounded-md bg-gray-100 object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-16 w-24 shrink-0 items-center justify-center rounded-md bg-gray-100 text-xs text-gray-400">
                                No image
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            @if ($item->status)
                                <span class="text-xs font-semibold uppercase tracking-wide text-skilline-orange">
                                    {{ $item->status->name }}
                                </span>
                            @endif

                            <p class="mt-0.5 line-clamp-2 text-sm font-medium text-darken transition-colors group-hover:text-skilline-orange">
                                {{ $item->title }}
                            </p>

                            @if ($item->registration_ends_at)
                                <p class="mt-1 text-xs text-gray-500">
                                    Closes {{ $item->registration_ends_at->format('M j, Y') }}
                                </p>
                            @endif
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('competitions.index') }}" wire:navigate class="link-accent mt-6 inline-block text-sm">
        View all competitions &rarr;
    </a>
</aside>
