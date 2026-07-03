@extends('layouts.app')

@section('content')
    @if ($stickyPosts->isNotEmpty())
        <div class="bg-cream pt-10 lg:pt-20">
            <x-post-slider :posts="$stickyPosts" />
            <x-skilline-wave />
        </div>
    @else
        <section class="relative bg-cream">
            <x-skilline-hero-bg />

            <div class="relative z-10 mx-auto flex max-w-screen-xl flex-col items-start px-8 lg:flex-row">
                <div class="mb-5 flex w-full flex-col items-center text-center md:mb-0 lg:w-6/12 lg:items-start lg:pt-24 lg:text-left">
                    <h1 class="my-4 text-5xl font-bold leading-tight text-darken">
                        Welcome to <span class="text-skilline-orange">{{ config('app.name') }}</span>
                    </h1>
                    <p class="mb-8 text-2xl leading-normal text-gray-600">
                        Stories, updates, and insights from our team.
                    </p>
                    <a href="{{ route('posts.index') }}" wire:navigate class="btn-accent">
                        View all posts
                    </a>
                </div>
            </div>

            <x-skilline-wave />
        </section>
    @endif

    @if ($competitions->isNotEmpty())
        <section class="section-white">
            <div class="container mx-auto max-w-screen-xl px-8 py-20">
                <div class="mb-12 text-center">
                    <h2 class="text-3xl font-bold text-darken sm:text-4xl">
                        Competitions and Registration
                    </h2>
                    <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-gray-500 sm:text-lg">
                        Explore open competitions, categories, and registration deadlines.
                    </p>
                </div>

                <div class="space-y-16 lg:space-y-24">
                    @foreach ($competitions as $competition)
                        <x-competition-panel
                            :competition="$competition"
                            @class([
                                'border-b border-gray-100 pb-16 lg:pb-24' => ! $loop->last,
                            ])
                        />
                    @endforeach
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('competitions.index') }}" wire:navigate class="btn-read-article">
                        View all competitions
                    </a>
                </div>
            </div>
        </section>
    @endif

    @if ($featuredPost)
        <section class="container mx-auto max-w-screen-xl px-8 py-20 text-gray-700">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-bold text-darken sm:text-4xl">
                    Latest News and Resources
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-gray-500 sm:text-lg">
                    Catch up on the latest updates, stories, and resources from our team.
                </p>
            </div>

            <div @class([
                'grid gap-12',
                'lg:grid-cols-2 lg:gap-16' => $recentPosts->isNotEmpty(),
            ])>
                <x-post-news-featured :post="$featuredPost" />

                @if ($recentPosts->isNotEmpty())
                    <div class="flex flex-col justify-center gap-8 sm:gap-10">
                        @foreach ($recentPosts as $post)
                            <x-post-news-list-item :post="$post" />
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('posts.index') }}" wire:navigate class="btn-read-article">
                    View all articles
                </a>
            </div>
        </section>
    @elseif ($stickyPosts->isEmpty())
        <section class="mx-auto max-w-screen-xl px-8 py-14">
            <p class="text-center text-gray-500">No posts published yet.</p>
        </section>
    @endif
@endsection
