@extends('litstack::index')

@section('title')
    @isset($title)
        {{ strip_tags(ucfirst($title)) }}
    @endisset
@endsection

@section('content')
    @include('litstack::vue.component', [
        'component' => 'litstack-app',
        'props' => ((array) \Ignite\Support\Facades\VueApp::render())['props']
    ])
@endsection