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
    <!-- spinner -->
    <style type="text/css">
        .fjord-spinner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;

            pointer-events: none;
            opacity: 1;
            transition: 0.2s all ease;
        }
        .fjord-spinner.loaded{
            opacity: 0;
        }
        .lds-ripple {
            display: inline-block;
            position: relative;
            width: 200px;
            height: 200px;
        }
        .lds-ripple div {
            position: absolute;
            border: 5px solid #3490dc;
            opacity: 1;
            border-radius: 50%;

            animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }
        .lds-ripple div:nth-child(2) {
            animation-delay: -0.5s;
        }
        @keyframes lds-ripple {
            0% {
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                width: 200px;
                height: 200px;
                opacity: 0;
            }
        }
    </style>
</head>

<body onload="makeVisible()">
    <div id="app">
        <header>
            @include('fjord::partials.topbar')
        </header>
        @include('fjord::partials.navigation')
        <main>
            <div class="container fjord-container">
                @yield('content')
            </div>
            <div id="fjord-spinner"
            class="fjord-spinner d-flex justify-content-center align-items-center">
                <div class="lds-ripple d-flex justify-content-center align-items-center"><div></div><div></div></div>
            </div>
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
