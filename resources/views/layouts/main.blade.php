<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>LingapIskolar</title>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        />
        <script
            defer
            src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"
        ></script>
        @vite("resources/css/app.css")
    </head>
    <body
        class="flex min-h-screen min-w-screen flex-col overflow-x-hidden"
        x-data="{ sidebarOpen: false }"
    >
        <header
            class="sticky top-0 z-50 flex h-20 items-center justify-between border-b border-zinc-100 bg-white px-10 shadow-sm"
        >
            <div class="flex items-center gap-4">
                @auth
                    <i
                        @click="sidebarOpen = !sidebarOpen"
                        class="bi bi-list cursor-pointer text-4xl md:hidden"
                    ></i>
                @endauth

                <a
                    href="{{ route("root") }}"
                    class="transition-opacity hover:opacity-90"
                >
                    <img src="/img/home-logo.jpg" alt="Logo" class="w-52" />
                </a>
            </div>

            <div class="flex flex-1 items-center justify-end gap-6">
                @auth
                    @section("headerside")
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

        <main
            class="flex h-[calc(100vh-5rem)] overflow-hidden bg-cover bg-center bg-no-repeat"
            style="
                background-image: url('{{ asset(auth()->check() ? "/img/auth-bg.png" : "/img/public-bg.png") }}');
            "
        >
            @auth
                <div
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-zinc-200 bg-white/80 p-4 backdrop-blur-md transition-transform duration-300 md:static md:translate-x-0 md:bg-gray-200"
                >
                    <div class="flex w-full flex-1 flex-col gap-1">
                        @section("sidebar")
                        @show
                    </div>
                    <x-logout-modal />
                </div>
                <div
                    x-show="sidebarOpen"
                    @click="sidebarOpen = false"
                    class="fixed inset-0 z-30 bg-black/20 md:hidden"
                ></div>
            @endauth

            <div class="flex-1 overflow-y-scroll bg-zinc-50/50">
                @auth
                    @if ($errors->any())
                        <div class="px-8 py-4">
                            <x-alert
                                :title="'Something went wrong!'"
                                :type="'danger'"
                            ></x-alert>
                        </div>
                    @endif
                @endauth

                @section("main")

                @show
            </div>
        </main>
    </body>
</html>
