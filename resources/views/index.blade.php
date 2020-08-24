<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title') - Lit</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{route('lit.favicon-big')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{route('lit.favicon-small')}}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link href="{{ route('lit.css') }}" rel="stylesheet"/>
    
    @foreach(lit_app()->getStyles() as $path)
        <link href="{{ $path }}{{ asset_time() }}" rel="stylesheet">
    @endforeach

    @include('lit::partials.google_analytics')

</head>

<body onload="makeVisible()">
    @php
        lit()->config('navigation')->topbar;
        lit()->config('navigation')->main;
    @endphp
    <div id="lit-app">

            @include('lit::partials.topbar.topbar')
            @include('lit::partials.navigation')
            <b-button
            variant="primary"
            class="d-block d-lg-none btn-square lit-main-navigation-toggle"
        >
            <fa-icon icon="stream" />
        </b-button>

        <main>
            <div class="lit-content">
                @yield('content') 
            </div>
            
             @include('lit::partials.spinner')
        </main>

    </div>

    <!-- lit translations -->
    <script src="{{ lit()->route('lit-translations') }}"></script>
    <!-- lit app js -->
    <script src="{{ lit_js() }}" defer></script>

    @foreach(lit_app()->getScripts() as $src)
        <script src="{{ $src }}"></script>
    @endforeach

    <script type="text/javascript">
        function makeVisible(){
            let spinner = document.getElementById("lit-spinner");
            let main = document.querySelector("div#lit-app > main");
            if(spinner && main) {
                spinner.classList.add('loaded');
                main.classList.add('loaded');
            }
        
            main.addEventListener('scroll', e => {
                //let container = document.querySelector('.lit-container');
                let header = document.querySelector('.lit-page-navigation');

                let toasterSlot = document.querySelector('.b-toaster-slot');

                if(!header) {
                    return;
                }
                
                if(header.getBoundingClientRect().top == 0){
                    header.classList.add('sticky')
                    if(toasterSlot){
                        toasterSlot.classList.add('sticky')
                    }
                    
                } else {
                    header.classList.remove('sticky')
                    if(toasterSlot){
                        toasterSlot.classList.remove('sticky')
                    }
                }
                
            });

            
            const toggleSidebar = () => {
                document
                .querySelector('.lit-navigation')
                .classList.toggle('visible');
                
                document
                .querySelector('#lit-app')
                .classList.toggle('navigation-visible');
            }
            
  
            document.querySelector('.lit-main-navigation-toggle').addEventListener('click', e => {
                toggleSidebar()
            })
        }
    </script>
</body>

</html>
