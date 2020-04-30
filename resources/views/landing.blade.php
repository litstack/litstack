@extends('fjord::index', [
    'nav' => false
])

@section('title')
    @yield('l-title')
@endsection

@section('content')
    <div class="fj-container fj-landing-page container sm">

        <div class="text-center">
            <div class="fj-brand">
                @include('fjord::partials.logo')
            </div>
        </div>
        
        @yield('l-content')

    </div>
@endsection