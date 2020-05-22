<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title') - Fjord</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{route('fjord.favicon-big')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{route('fjord.favicon-small')}}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link href="{{ route('fjord.css') }}" rel="stylesheet"/>
    
    @foreach(fjord_app()->getCssFiles() as $path)
        <link href="{{ $path }}{{ asset_time() }}" rel="stylesheet">
    @endforeach

    @include('fjord::partials.google_analytics')

</head>

<body onload="makeVisible()">
    @php
        fjord()->config('navigation')->topbar;
        fjord()->config('navigation')->main;
    @endphp
    <div id="fjord-app">

            @include('fjord::partials.topbar.topbar')
            @include('fjord::partials.navigation')
            <b-button
            variant="primary"
            class="d-block d-lg-none btn-square fj-main-navigation-toggle"
        >
            <fa-icon icon="stream" />
        </b-button>

        <main>
            <div class="fj-content">
                @yield('content') 
            </div>
            
             @include('fjord::partials.spinner')
        </main>

    </div>

    <!-- fjord translations -->
    <script src="{{ fjord()->route('fjord-translations') }}"></script>
    <!-- fjord app js -->
    <script src="{{ fjord_js() }}" defer></script>

    <script type="text/javascript">
        function makeVisible(){
            let spinner = document.getElementById("fj-spinner");
            let main = document.querySelector("div#fjord-app > main");
            if(spinner && main) {
                spinner.classList.add('loaded');
                main.classList.add('loaded');
            }
        
            main.addEventListener('scroll', e => {
                //let container = document.querySelector('.fj-container');
                let header = document.querySelector('.fj-page-navigation');

                let toasterSlot = document.querySelector('.b-toaster-slot');

                if(!header) {
                    return;
                }
                
                if(header.getBoundingClientRect().top == 0){
                    header.classList.add('sticky')
                    if(toasterSlot){
                        toasterSlot.classList.add('sticky')
                    }
                    
                }else{
                    header.classList.remove('sticky')
                    if(toasterSlot){
                        toasterSlot.classList.remove('sticky')
                    }
                }
                
            });

            
            const toggleSidebar = () => {
                document
                .querySelector('.fj-navigation')
                .classList.toggle('visible');
                
                document
                .querySelector('#fjord-app')
                .classList.toggle('navigation-visible');
            }
            
  
            document.querySelector('.fj-main-navigation-toggle').addEventListener('click', e => {
                toggleSidebar()
            })
        }
    </script>
</body>

</html>
