@extends('fjord::index')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @php
        //dd(\Fjord\Support\Facades\VueApp::props());
    @endphp
    @include('fjord::vue.component', [
        'component' => 'fjord-app',
        'props' => \Fjord\Support\Facades\VueApp::props()
    ])
@endsection
