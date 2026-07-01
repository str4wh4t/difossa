@extends('layouts.app')

@section('content')
    <div class="relative bg-cream">
        <div class="relative z-10 mx-auto max-w-screen-xl px-8 py-10 lg:py-12">
            <h1 class="max-w-3xl text-3xl font-bold text-darken sm:text-4xl lg:text-5xl">
                {{ $page->title }}
            </h1>
        </div>
        <x-skilline-wave />
    </div>

    <div class="section-white mx-auto max-w-screen-xl px-8 py-12 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-3">
            <article class="content-panel lg:col-span-2">
                <div class="cms-content">
                    {!! $page->content !!}
                </div>
            </article>

            <x-other-articles :posts="$otherPosts" />
        </div>
    </div>
@endsection
