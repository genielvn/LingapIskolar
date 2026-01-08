<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>LingapIskolar</title>
        @vite("resources/css/app.css")
    </head>
    <body class="flex min-h-screen min-w-screen flex-col overflow-x-hidden">
        <x-header />
        <main
            class="h-full flex-1 p-4"
            style="
                background-image: url('{{ asset(auth()->check() ? "/img/auth-bg.png" : "/img/public-bg.png") }}');
            "
        >
            @section("main")

            @show
        </main>
    </body>
</html>
