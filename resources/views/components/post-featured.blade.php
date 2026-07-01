@props(['post'])

<article class="site-surface overflow-hidden lg:flex lg:items-stretch">
    @if ($post->featured_image_url || $post->thumbnail_url)
        <a
            href="{{ route('posts.show', $post) }}"
            wire:navigate
            class="relative block aspect-video overflow-hidden bg-gray-100 lg:aspect-auto lg:w-2/5 lg:shrink-0"
        >
            <div class="absolute -left-2 -top-2 z-0 h-16 w-16 rounded-lg bg-skilline-sky" aria-hidden="true"></div>
            <img
                src="{{ $post->featured_image_url ?? $post->thumbnail_url }}"
                alt="{{ $post->title }}"
                class="relative z-10 h-full w-full object-cover"
                loading="lazy"
            >
        </a>
    @endif

    <div class="flex flex-1 flex-col justify-center p-6 sm:p-8">
        <span class="text-xs font-semibold uppercase tracking-widest text-skilline-orange">Featured</span>

        @if ($post->published_at)
            <time datetime="{{ $post->published_at->toIso8601String() }}" class="mt-2 text-sm text-gray-500">
                {{ $post->published_at->format('F j, Y') }}
            </time>
        @endif

        <h2 class="mt-2 text-2xl font-bold text-darken sm:text-3xl">
            <a href="{{ route('posts.show', $post) }}" wire:navigate class="transition-colors hover:text-skilline-orange">
                {{ $post->title }}
            </a>
        </h2>

        @if ($post->excerpt)
            <p class="mt-3 line-clamp-3 text-base leading-relaxed text-black">
                {{ $post->excerpt }}
            </p>
        @endif

        <a href="{{ route('posts.show', $post) }}" wire:navigate class="btn-read-article mt-6">
            Read article
        </a>
    </div>
</article>
