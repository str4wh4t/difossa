@props(['post', 'badge' => 'News'])

<article>
    <a href="{{ route('posts.show', $post) }}" wire:navigate class="group flex gap-4 sm:gap-5">
        <div class="relative shrink-0">
            @if ($post->thumbnail_url || $post->featured_image_url)
                <img
                    src="{{ $post->thumbnail_url ?? $post->featured_image_url }}"
                    alt="{{ $post->title }}"
                    class="h-24 w-32 rounded-xl bg-gray-100 object-cover sm:h-28 sm:w-36"
                    loading="lazy"
                >
            @else
                <div class="flex h-24 w-32 items-center justify-center rounded-xl bg-gray-100 text-xs text-gray-400 sm:h-28 sm:w-36">
                    No image
                </div>
            @endif

            <span class="absolute bottom-2 right-2 rounded-full bg-[#FFD22F] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-darken">
                {{ $badge }}
            </span>
        </div>

        <div class="min-w-0 flex-1">
            <h3 class="line-clamp-2 text-lg font-bold leading-snug text-darken transition-colors group-hover:text-skilline-orange sm:text-xl">
                {{ $post->title }}
            </h3>

            @if ($post->excerpt)
                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-gray-500 sm:text-base">
                    {{ $post->excerpt }}
                </p>
            @endif
        </div>
    </a>
</article>
