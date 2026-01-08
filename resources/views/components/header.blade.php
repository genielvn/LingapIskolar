<header class="box-border flex flex-row items-center justify-between px-8 py-4">
    <div class="left text-3xl">LingapIskolar</div>
    <div class="right flex gap-4">
        @auth
            
        @endauth

        @guest
            <x-button :href="route('login')">Log In</x-button>
            <x-button :href="route('signup')">Sign Up</x-button>
        @endguest
    </div>
</header>
