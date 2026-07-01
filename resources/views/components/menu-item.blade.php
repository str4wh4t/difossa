@props(['item', 'class' => '', 'showChildren' => true])

@php
    $url = $item->resolveUrl();
@endphp

@if ($url)
    <a
        href="{{ $url }}"
        @if ($item->opensInNewTab()) target="_blank" rel="noopener noreferrer" @else wire:navigate @endif
        {{ $attributes->merge(['class' => $class]) }}
    >
        {{ $item->label }}
    </a>
@endif

@if ($showChildren && $item->children->isNotEmpty())
    <ul class="mt-1 space-y-1 border-l-2 border-skilline-cyan/40 pl-4">
        @foreach ($item->children as $child)
            <li>
                <x-menu-item :item="$child" :class="$class" />
            </li>
        @endforeach
    </ul>
@endif
