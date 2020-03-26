@extends('fjord::html')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @php
    fjord()->app()->build(get_defined_vars());
    @endphp
    <fjord-app
        @foreach (fjord()->app()->getProps() as $key => $prop)
            @if(is_string($prop))
                @php
                    $prop = "'".$prop."'";
                @endphp
            @endif

            @if (is_bool($prop))
                @if ($prop)
                    :{{$key}}=true
                @else
                    :{{$key}}=false
                @endif
            @else
                :{{$key}}="{{$prop}}"
            @endif
        @endforeach></fjord-app>
@endsection
