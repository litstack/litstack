@extends('fjord::html')

@section('title')
    @isset($title)
        {{ucfirst($title)}}
    @endisset
@endsection

@section('content')
    @php
        //dd(->first(), auth()->user()->roles->first()->permissions->first());


        // TODO: auslagern

        // Permissions
        $permissions = collect([]);
        foreach(auth()->user()->roles as $role) {
            $permissions = $permissions->merge(
                $role->permissions->pluck('name')
            );
        }

        $fjProps = [
            'component' => $component,
            'props' => collect($props ?? []),
            'models' => collect([]),
            'translatable' => collect([
                'language' => app()->getLocale(),
                'languages' => collect(config('translatable.locales')),
                'fallback_locale' => config('translatable.fallback_locale'),
            ]),
            'config' => collect(config('fjord')),
            'auth' => auth()->user(),
            'permissions' => $permissions->unique()
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
