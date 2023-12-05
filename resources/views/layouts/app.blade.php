<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/plugins/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/animate.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/fontawsome/css/font-awesome.min.css') }}">
    <link href="{{ asset('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
</head>
<body>
    <div id="app">

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('assets/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/nprogress/nprogress.js') }}"></script>
</body>
</html>
