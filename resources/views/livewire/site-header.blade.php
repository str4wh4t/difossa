<header class="w-full bg-cream text-gray-700" x-data="{ open: false }">
    <div class="mx-auto flex max-w-screen-xl flex-col px-8 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-row items-center justify-between py-6">
            <div class="md:mt-8">
                <x-site-logo />
            </div>

            @if ($headerMenu)
                <button
                    type="button"
                    class="rounded-lg focus:outline-none md:hidden"
                    @click="open = !open"
                    :aria-expanded="open"
                    aria-label="Toggle menu"
                >
                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 w-6">
                        <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd" />
                        <path x-show="open" x-cloak fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>

        @if ($headerMenu)
            <nav
                class="hidden flex-grow flex-col pb-4 md:flex md:h-auto md:flex-row md:items-center md:justify-end md:pb-0"
                aria-label="Main navigation"
            >
                @foreach ($headerMenu->items as $item)
                    <x-menu-item
                        :item="$item"
                        :show-children="false"
                        class="mt-2 rounded-lg bg-transparent px-4 py-2 text-sm hover:text-gray-900 focus:outline-none md:ml-4 md:mt-8"
                    />
                @endforeach
            </nav>

            <nav
                x-show="open"
                x-cloak
                x-transition
                class="flex flex-col border-t border-white/60 pb-4 md:hidden"
                aria-label="Mobile navigation"
            >
                @foreach ($headerMenu->items as $item)
                    <x-menu-item
                        :item="$item"
                        class="mt-2 rounded-lg px-4 py-2 text-sm hover:bg-white/60 hover:text-gray-900"
                    />
                @endforeach
            </nav>
        @endif
    </div>
</header>
