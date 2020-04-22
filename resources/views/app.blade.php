@extends('fjord::index')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @php
        //dd(collect(\Fjord\Support\Facades\VueApp::props())->__toString());
    @endphp
    @include('fjord::vue.component', [
        'component' => 'fjord-app',
        'props' => \Fjord\Support\Facades\VueApp::props()
    ])
@endsection