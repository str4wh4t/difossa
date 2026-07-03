@extends('layouts.app')

@section('content')
    <div class="relative bg-cream">
        <div class="relative z-10 mx-auto max-w-screen-xl px-8 py-12 lg:pt-16">
            <header class="max-w-3xl text-center lg:text-left">
                <h1 class="text-4xl font-bold leading-tight text-darken lg:text-5xl">
                    <span class="text-skilline-orange">Competitions</span> and Registration
                </h1>
                <p class="mt-4 text-xl leading-normal text-gray-600">
                    Explore open competitions, categories, and registration deadlines.
                </p>
            </header>
        </div>

        <x-skilline-wave />
    </div>

    <section class="section-white">
        <div class="container mx-auto max-w-screen-xl px-8 py-16">
            @if ($competitions->isEmpty())
                <p class="text-center text-gray-500">No competitions available yet.</p>
            @else
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

                <div class="mt-12">
                    {{ $competitions->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
