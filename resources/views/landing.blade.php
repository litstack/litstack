<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{route('lit.favicon-big')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{route('lit.favicon-small')}}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link href="{{ route('lit.css') }}" rel="stylesheet"/>

    @include('litstack::partials.google_analytics')
</head>


<body onload="makeVisible()">
    <div id="litstack" class="no-nav">

        <main>
            <div class="lit-content">
                <div class="lit-container lit-landing-page container sm pt-5">

                    {{-- <div class="text-center">
                        <div class="lit-brand">
                            @include('litstack::partials.logo')
                        </div>
                    </div> --}}
                    
                    @yield('content')
            
                </div>
            </div>
            
             @include('litstack::partials.spinner')
        </main>
        

    </div>

    <script src="{{ Lit::route('app2.js') }}" defer></script>

    <script type="text/javascript">
        function makeVisible(){
            let spinner = document.getElementById("lit-spinner");
            let main = document.querySelector("div#litstack > main");
            if(spinner && main) {
                spinner.classList.add('loaded');
                main.classList.add('loaded');
            }
        }
    </script>
</body>

</html>