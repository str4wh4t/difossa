@extends('layouts.app')

@section('content')
    <div class="relative bg-cream">
        <div class="relative z-10 mx-auto max-w-screen-xl px-8 py-10 lg:py-12">
            <div class="max-w-3xl">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-sm font-semibold uppercase tracking-widest text-skilline-orange">
                        {{ $post->published_at->format('F j, Y') }}
                    </time>
                @endif
                <h1 class="mt-3 text-3xl font-bold text-darken sm:text-4xl lg:text-5xl">
                    {{ $post->title }}
                </h1>
            </div>
        </div>
        <x-skilline-wave />
    </div>

    <div class="section-white mx-auto max-w-screen-xl px-8 py-12 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-3">
            <article class="content-panel lg:col-span-2">
                @if ($post->featured_image_url)
                    <figure class="mb-10 overflow-hidden rounded-2xl bg-gray-100">
                        <img
                            src="{{ $post->featured_image_url }}"
                            alt="{{ $post->title }}"
                            class="w-full object-cover"
                        >
                    </figure>
                @endif

                <div class="cms-content">
                    {!! $post->content !!}
                </div>

                <footer class="mt-12 border-t border-gray-200 pt-8">
                    <a href="{{ route('posts.index') }}" wire:navigate class="link-accent text-sm">
                        &larr; Back to blog
                    </a>
                </footer>
            </article>

            <x-other-articles :posts="$otherPosts" />
        </div>
    </div>
@endsection

@push('meta')
    @if ($post->featured_image_url)
        <meta property="og:image" content="{{ $post->featured_image_url }}">
    @endif
@endpush
