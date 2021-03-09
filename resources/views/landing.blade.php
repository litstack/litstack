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

    @foreach(lit_app()->getStyles() as $path)
        <link href="{{ $path }}{{ asset_time() }}" rel="stylesheet">
    @endforeach

    @include('litstack::partials.google_analytics')
</head>

<body class="overflow-hidden">
     @yield('content')

    <script src="{{ Lit::route('app2.js') }}" defer></script>
</body>
</html>
