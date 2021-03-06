<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('page_script')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('page_style')
</head>
<body id="app" class="d-flex flex-column justify-content-between" style="height: 100vh">
    <header class="mb-2">
        @include('layouts.inc.header')
    </header>

    <main role="main" class="py-4 container mt- mb-auto">
        @yield('content')
    </main>

    <footer class="footer mt-2" style="background-color: #EEEEEE">
        @include('layouts.inc.footer')
    </footer>
</body>
</html>
