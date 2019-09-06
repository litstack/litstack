<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link href="{{ asset('fjord/css/app.css') }}?t={{ filemtime(public_path('fjord/css/app.css')) }}" rel="stylesheet">

</head>

<body onload="makeVisible()">
    <div id="fjord-app" class="{{ config('fjord.layout') }}">

        @include('fjord::partials.topbar')

        @include('fjord::partials.navigation')

        <main>
            @yield('content')
            @include('fjord::partials.spinner')
        </main>

        <notify />

    </div>

    <script src="{{ asset('fjord/js/app.js') }}?t={{ filemtime(public_path('fjord/js/app.js')) }}" defer></script>
    <script type="text/javascript">
        function makeVisible(){
            var d = document.getElementById("fjord-spinner");
            d.className += " loaded";
        }
    </script>
</body>

</html>
