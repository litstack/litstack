@extends('fjord::app')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @php

        $fjProps = [
            'component' => $component,
            'props' => collect($props ?? []),
            'models' => collect([]),
            'language' => app()->getLocale(),
            'languages' => collect(config('translatable.locales'))
        ];

        foreach($models ?? [] as $title => $model) {
            $fjProps['models'][$title] = $model->toArray();
        }
    @endphp
    <fjord-app
        @foreach ($fjProps as $key => $prop)
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
