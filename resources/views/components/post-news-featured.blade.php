@props(['post', 'badge' => 'News'])

<article>
    @if ($post->featured_image_url || $post->thumbnail_url)
        <a
            href="{{ route('posts.show', $post) }}"
            wire:navigate
            class="block overflow-hidden rounded-2xl bg-gray-100"
        >
            <img
                src="{{ $post->featured_image_url ?? $post->thumbnail_url }}"
                alt="{{ $post->title }}"
                class="aspect-[16/10] w-full object-cover transition-transform duration-300 hover:scale-105"
                loading="lazy"
            >
        </a>
    @endif

    <span class="mt-5 inline-flex rounded-full bg-[#FFD22F] px-3 py-1 text-xs font-bold uppercase tracking-wide text-darken">
        {{ $badge }}
    </span>

    <h3 class="mt-4 text-2xl font-bold leading-snug text-darken sm:text-3xl">
        <a href="{{ route('posts.show', $post) }}" wire:navigate class="transition-colors hover:text-skilline-orange">
            {{ $post->title }}
        </a>
    </h3>

    @if ($post->excerpt)
        <p class="mt-4 line-clamp-4 text-base leading-relaxed text-gray-600">
            {{ $post->excerpt }}
        </p>
    @endif

    <a
        href="{{ route('posts.show', $post) }}"
        wire:navigate
        class="link-accent mt-6 inline-block text-base underline decoration-darken/30 underline-offset-8 transition-colors hover:decoration-skilline-orange"
    >
        Read more
    </a>
</article>
