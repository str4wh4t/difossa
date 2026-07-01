@props(['posts'])

@if ($posts->isNotEmpty())
    <div
        class="relative z-10 pb-4"
        x-data="{
            active: 0,
            total: {{ $posts->count() }},
            next() { this.active = (this.active + 1) % this.total },
            prev() { this.active = (this.active - 1 + this.total) % this.total },
            goTo(index) { this.active = index },
        }"
        role="region"
        aria-roledescription="carousel"
    >
        <div class="relative mx-auto max-w-screen-xl overflow-visible px-6 sm:px-10 lg:px-14">
            <div class="relative min-h-[28rem] pb-10 sm:min-h-[26rem] sm:pb-12 lg:min-h-[22rem]">
                @foreach ($posts as $index => $post)
                    <div
                        x-show="active === {{ $index }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-6"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-6"
                        @if ($index > 0) x-cloak @endif
                        class="absolute inset-0 flex items-center"
                        :class="active === {{ $index }} ? 'pointer-events-auto z-10' : 'pointer-events-none z-0'"
                        role="group"
                        aria-roledescription="slide"
                        :aria-hidden="active !== {{ $index }}"
                    >
                        <div class="grid w-full gap-10 lg:grid-cols-2 lg:items-center lg:py-8">
                            <div class="order-2 flex flex-col items-center text-center lg:order-1 lg:items-start lg:text-left">
                                @if ($post->published_at)
                                    <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-sm font-semibold uppercase tracking-widest text-skilline-orange">
                                        {{ $post->published_at->format('F j, Y') }}
                                    </time>
                                @endif

                                <h2 class="mt-3 text-3xl font-bold leading-tight text-darken sm:text-4xl lg:text-5xl">
                                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="transition-colors hover:text-skilline-orange">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                @if ($post->excerpt)
                                    <p class="mt-4 line-clamp-4 text-lg leading-relaxed text-gray-600">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif

                                <a href="{{ route('posts.show', $post) }}" wire:navigate class="btn-read-article relative z-10 mt-8">
                                    Read article
                                </a>
                            </div>

                            <div class="relative order-1 lg:order-2">
                                <div class="floating absolute -left-3 -top-3 z-0 h-20 w-20 rounded-xl bg-skilline-sky lg:h-24 lg:w-24" aria-hidden="true"></div>

                                @if ($post->featured_image_url || $post->thumbnail_url)
                                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="relative z-10 block aspect-video overflow-hidden rounded-2xl bg-white shadow-xl">
                                        <img
                                            src="{{ $post->featured_image_url ?? $post->thumbnail_url }}"
                                            alt="{{ $post->title }}"
                                            class="h-full w-full object-cover"
                                        >
                                    </a>
                                @else
                                    <div class="relative z-10 flex aspect-video items-center justify-center rounded-2xl bg-white/80 text-sm text-gray-500 shadow-xl">
                                        No image
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($posts->count() > 1)
                <button
                    type="button"
                    class="btn-slider-nav absolute top-1/2 z-20 -translate-y-1/2 left-0 sm:-left-3 lg:-left-5"
                    @click="prev()"
                    aria-label="Previous slide"
                >
                    <svg class="h-6 w-6 sm:h-7 sm:w-7" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <button
                    type="button"
                    class="btn-slider-nav absolute top-1/2 z-20 -translate-y-1/2 right-0 sm:-right-3 lg:-right-5"
                    @click="next()"
                    aria-label="Next slide"
                >
                    <svg class="h-6 w-6 sm:h-7 sm:w-7" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <div class="flex justify-center gap-2 pb-6 pt-2">
                    @foreach ($posts as $index => $post)
                        <button
                            type="button"
                            class="h-2.5 w-2.5 rounded-full transition-colors"
                            :class="active === {{ $index }} ? 'bg-skilline-orange' : 'bg-skilline-orange/30 hover:bg-skilline-orange/50'"
                            @click="goTo({{ $index }})"
                            :aria-current="active === {{ $index }} ? 'true' : 'false'"
                            aria-label="Go to slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @else
                <div class="pb-8"></div>
            @endif
        </div>
    </div>
@endif
