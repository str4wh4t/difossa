@props(['class' => ''])

<a
    href="{{ route('home') }}"
    wire:navigate
    {{ $attributes->merge(['class' => 'text-lg font-bold tracking-widest text-gray-900 rounded-lg focus:outline-none ' . $class]) }}
>
    {{ config('app.name') }}
</a>
