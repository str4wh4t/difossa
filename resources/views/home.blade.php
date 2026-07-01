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

    @if ($posts->isNotEmpty())
        <section class="container mx-auto max-w-screen-xl overflow-x-hidden px-8 py-20 text-gray-700">
            <div class="mb-10 text-center">
                <h2 class="my-3 text-2xl font-bold text-darken">
                    Latest <span class="text-skilline-orange">Posts</span>
                </h2>
                <p class="leading-relaxed text-gray-500">Recent articles and updates from our team.</p>
            </div>

            <div class="grid gap-14 md:grid-cols-2 md:gap-5 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('posts.index') }}" wire:navigate class="btn-outline">
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
