<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'TimeQuest')</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Cormorant:ital,wght@0,300;0,400;0,600;1,400&family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">
    </head>
    <body class="bg-gray-100">
        @include('components.navbar')
        <main>
            @yield('content')
        </main>

    </body>
</html>
