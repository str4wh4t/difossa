@props(['post'])

<article class="site-surface group flex flex-col overflow-hidden text-center">
  @if ($post->thumbnail_url)
    <a href="{{ route('posts.show', $post) }}" wire:navigate class="block aspect-video overflow-hidden bg-gray-100">
      <img
        src="{{ $post->thumbnail_url }}"
        alt="{{ $post->title }}"
        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
        loading="lazy"
      >
    </a>
  @endif

  <div class="flex flex-1 flex-col p-6">
    @if ($post->published_at)
      <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-xs font-semibold uppercase tracking-widest text-skilline-orange">
        {{ $post->published_at->format('M j, Y') }}
      </time>
    @endif

    <h2 class="mt-2 text-xl font-medium text-darken lg:px-4">
      <a href="{{ route('posts.show', $post) }}" wire:navigate class="transition-colors hover:text-skilline-orange">
        {{ $post->title }}
      </a>
    </h2>

    @if ($post->excerpt)
      <p class="mt-2 line-clamp-3 flex-1 px-4 text-sm leading-relaxed text-gray-500">
        {{ $post->excerpt }}
      </p>
    @endif

    <a
      href="{{ route('posts.show', $post) }}"
      wire:navigate
      class="mt-4 inline-flex items-center justify-center text-sm font-medium text-darken transition-colors hover:text-skilline-orange"
    >
      Read more
      <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
      </svg>
    </a>
  </div>
</article>
