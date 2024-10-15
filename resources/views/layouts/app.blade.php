<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TimeQuest')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Cormorant:ital,wght@0,300;0,400;0,600;1,400&family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
        }
        .font-display {
            font-family: 'Cinzel', serif;
        }
        .font-serif {
            font-family: 'Cormorant', serif;
        }
    </style>
</head>
<body class="bg-navbar-bg text-menu-text">
    @include('components.navbar')
    <main>
        @yield('content')
    </main>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>