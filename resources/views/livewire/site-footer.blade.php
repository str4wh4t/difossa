<footer class="mt-auto bg-white text-gray-600">
    <div class="mx-auto max-w-screen-xl px-8 py-12">
        @if ($footerMenu)
            <nav class="flex flex-wrap justify-center gap-x-8 gap-y-3 md:justify-end" aria-label="Footer navigation">
                @foreach ($footerMenu->items as $item)
                    <x-menu-item :item="$item" class="text-sm hover:text-gray-900" />
                @endforeach
            </nav>
        @endif

        <div @class(['border-t border-gray-100 pt-6 text-center text-sm text-gray-400 md:text-left', 'mt-10' => $footerMenu])>
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</footer>
