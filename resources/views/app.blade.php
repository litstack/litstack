<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzfU1NLWVY5qSV_JOLK7KFivMt3L8CS38&libraries=places"
    ></script>

    @guest
    <style media="screen">
        #app {
            grid-template:
                't t'
                'm m' !important;
        }
    </style>
    @endguest

    <link href="{{ asset('fjord/css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        @include('fjord::partials.topbar')
        @include('fjord::partials.sidebar')
        <main>
            <div class="container">
                <h6 class="mt-3 mb-4 text-muted">
                    <i class="fas fa-angle-right"></i>
                    @yield('title')
                </h6>
                @yield('content')
            </div>
        </main>
        <notify />
    </div>
    <script src="{{ asset('fjord/js/app.js') }}" defer></script>
</body>

</html>
