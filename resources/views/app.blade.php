@extends('fjord::index')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @php
        //dd(\AwStudio\Fjord\Support\Facades\VueApp::props());
    @endphp
    @include('fjord::vue.component', [
        'component' => 'fjord-app',
        'props' => \AwStudio\Fjord\Support\Facades\VueApp::props()
    ])
@endsection
