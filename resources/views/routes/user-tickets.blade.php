@extends("layouts.main")

@section("main")
    <div class="flex w-full flex-col items-center justify-center gap-4">
        <div class="text-center">
            <h1 class="text-2xl font-bold">
                Welcome, {{ auth()->user()->name }}!
            </h1>
            <p class="text-gray-600">{{ auth()->user()->email }}</p>
        </div>
        <p>Page not implemented yet. Go and log out for testing</p>
        <form method="POST" action="/logout">
            @csrf
            <x-button :type="'submit'">Log Out</x-button>
        </form>
    </div>
@endsection
