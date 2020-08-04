@extends('fjord::index')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @include('fjord::vue.component', [
        'component' => 'fjord-app',
        'props' => ((array) \Fjord\Support\Facades\VueApp::render())['props']
    ])
@endsection