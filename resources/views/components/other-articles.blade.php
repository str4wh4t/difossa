@props(['posts'])

<aside class="content-panel lg:sticky lg:top-24 lg:self-start">
    <h2 class="border-b border-gray-200 pb-3 text-lg font-bold text-darken">
        Other Articles
    </h2>

    @if ($posts->isEmpty())
        <p class="mt-4 text-sm text-gray-500">No other articles yet.</p>
    @else
        <ul class="mt-4 space-y-4">
            @foreach ($posts as $post)
                <li>
                    <a
                        href="{{ route('posts.show', $post) }}"
                        wire:navigate
                        class="group flex gap-3"
                    >
                        @if ($post->thumbnail_url)
                            <img
                                src="{{ $post->thumbnail_url }}"
                                alt="{{ $post->title }}"
                                class="h-16 w-24 shrink-0 rounded-md object-cover bg-gray-100"
                                loading="lazy"
                            >
                        @endif

                        <div class="min-w-0 flex-1">
                            @if ($post->published_at)
                                <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-xs text-gray-500">
                                    {{ $post->published_at->format('M j, Y') }}
                                </time>
                            @endif

                            <p class="mt-0.5 line-clamp-2 text-sm font-medium text-darken transition-colors group-hover:text-skilline-orange">
                                {{ $post->title }}
                            </p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('posts.index') }}" wire:navigate class="link-accent mt-6 inline-block text-sm">
        View all articles &rarr;
    </a>
</aside>
