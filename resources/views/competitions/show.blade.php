@extends('layouts.app')

@section('content')
    @php
        $bannerUrl = $competition->banner_image_url ?? $competition->banner_thumbnail_url;
        $isOpen = $competition->isOpenForRegistration();
    @endphp

    <div class="relative bg-cream">
        <div class="relative z-10 mx-auto max-w-screen-xl px-8 py-10 lg:py-12">
            <div class="max-w-3xl">
                @if ($competition->status)
                    <span class="text-sm font-semibold uppercase tracking-widest text-skilline-orange">
                        {{ $competition->status->name }}
                    </span>
                @endif

                <h1 class="mt-3 text-3xl font-bold text-darken sm:text-4xl lg:text-5xl">
                    {{ $competition->title }}
                </h1>
            </div>
        </div>

        <x-skilline-wave />
    </div>

    <section class="section-white">
        <div class="mx-auto max-w-screen-xl px-8 py-12 lg:py-16">
            <div class="grid gap-10 lg:grid-cols-3">
                <article class="content-panel lg:col-span-2">
                    @if ($bannerUrl)
                        <figure class="mb-10 overflow-hidden rounded-2xl bg-gray-100 shadow-xl">
                            <img
                                src="{{ $bannerUrl }}"
                                alt="{{ $competition->title }}"
                                class="aspect-video w-full object-cover"
                            >
                        </figure>
                    @endif

                    <x-competition-registration-window :competition="$competition" class="mb-10" />

                    @if (filled($competition->description))
                        <div class="cms-content">
                            {!! $competition->description !!}
                        </div>
                    @endif

                    @if ($isOpen)
                        <div class="mt-10 border-t border-gray-200 pt-8">
                            <a href="{{ url('/admin/register') }}" class="btn-accent-block">
                                Register now
                            </a>
                        </div>
                    @endif

                    <footer class="mt-12 border-t border-gray-200 pt-8">
                        <a href="{{ route('competitions.index') }}" wire:navigate class="link-accent text-sm">
                            &larr; Back to competitions
                        </a>
                    </footer>
                </article>

                <x-other-competitions :competitions="$otherCompetitions" />
            </div>
        </div>
    </section>
@endsection

@push('meta')
    @if ($bannerUrl)
        <meta property="og:image" content="{{ $bannerUrl }}">
    @endif
@endpush
