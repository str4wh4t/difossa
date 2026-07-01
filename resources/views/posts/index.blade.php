@extends('layouts.app')

@section('content')
    <div class="relative bg-cream">
        <div class="relative z-10 mx-auto max-w-screen-xl px-8 py-12 lg:pt-16">
            <header class="max-w-3xl text-center lg:text-left">
                <h1 class="text-4xl font-bold leading-tight text-darken lg:text-5xl">
                    <span class="text-skilline-orange">Blog</span> Articles
                </h1>
                <p class="mt-4 text-xl leading-normal text-gray-600">Articles and updates.</p>
            </header>
        </div>

        <x-skilline-wave />
    </div>

    <div class="container mx-auto max-w-screen-xl overflow-x-hidden px-8 py-16 text-gray-700">
        @if ($stickyPosts->isNotEmpty())
            <section class="mb-16" aria-label="Featured posts">
                <x-post-slider :posts="$stickyPosts" />
            </section>
        @endif

        @if ($posts->isEmpty() && $stickyPosts->isEmpty())
            <p class="text-center text-gray-500">No posts published yet.</p>
        @elseif ($posts->isNotEmpty())
            @if ($stickyPosts->isNotEmpty())
                <h2 class="mb-8 text-center text-2xl font-bold text-darken md:text-left">
                    More <span class="text-skilline-orange">Articles</span>
                </h2>
            @endif

            <div class="grid gap-14 md:grid-cols-2 md:gap-5 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
