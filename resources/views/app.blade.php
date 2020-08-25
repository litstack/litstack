@extends('litstack::index')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @include('litstack::vue.component', [
        'component' => 'lit-app',
        'props' => ((array) \Lit\Support\Facades\VueApp::render())['props']
    ])
@endsection