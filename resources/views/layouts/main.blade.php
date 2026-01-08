<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>LingapIskolar</title>
        @vite("resources/css/app.css")
    </head>
    <body class="min-h-screen min-w-screen flex-row overflow-x-hidden">
        <x-header />
        @section("content")
        @show
    </body>
</html>
