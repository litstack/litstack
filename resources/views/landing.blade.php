@extends('fjord::index'[
    'noNav' => true
])

@section('title')
    @yield('l-title')
@endsection

@section('content')
    <div class="fj-container fj-landing-page container sm">

        <div class="text-center">
            <div class="fj-brand">
                <img src="{{ route('fjord.logo') }}" class="img-fluid">
            </div>
        </div>
        
        @yield('l-content')

    </div>
@endsection