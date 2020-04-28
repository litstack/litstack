<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Fjord - @yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{route('fjord.favicon-big')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{route('fjord.favicon-small')}}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link href="{{ fjord_css() }}{{ asset_time() }}" rel="stylesheet"/>
    
    @foreach(fjord_app()->getCssFiles() as $path)
        <link href="{{ $path }}{{ asset_time() }}" rel="stylesheet">
    @endforeach

</head>


<body onload="makeVisible()">
    <div id="fjord-app" class="{{ Auth::guard('fjord')->guest() ? '' : config('fjord.layout') }}">

        @include('fjord::partials.topbar.topbar')

        @include('fjord::partials.navigation')

        <main>
            @yield('content')
            @include('fjord::partials.spinner')
        </main>

    </div>

    <!-- fjord translations -->
    <script src="{{ fjord()->route('fjord-translations') }}"></script>
    <!-- fjord app js -->
    <script src="{{ fjord_js() }}" defer></script>

    <script type="text/javascript">
        function makeVisible(){
            var d = document.getElementById("fjord-spinner");
            if(d) {
                d.className += " loaded";
            }
        }
    </script>
</body>

</html>
