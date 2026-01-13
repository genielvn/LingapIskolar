<header
    class="sticky top-0 z-50 flex h-20 items-center justify-between border-b border-zinc-100 bg-white px-10 shadow-sm"
>
    <div class="flex items-center">
        <a
            href="{{ route("root") }}"
            class="transition-opacity hover:opacity-90"
        >
            <img src="/img/home-logo.jpg" alt="Logo" class="w-52" />
        </a>
    </div>

    <div class="flex flex-1 items-center justify-end gap-6">
        @auth
            @section("side")
            @show
        @endauth

        @guest
            <div class="flex items-center gap-3">
                <x-button :href="route('login')" :variant="'ghost'">
                    Log In
                </x-button>
                <x-button
                    :href="route('signup')"
                    class="min-w-0 px-8 shadow-md"
                >
                    Sign Up
                </x-button>
            </div>
        @endguest
    </div>
</header>
