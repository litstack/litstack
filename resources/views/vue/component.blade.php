@php
// Prepare props to not have dd being executed inside the html tag.
foreach($props as $key => $prop) {
    if(is_string($prop)) {
        $props[$key] = "'".$prop."'";
    }
    if(is_array($prop)) {
        $props[$key] = collect($prop)->toJson();
    }
    if($prop instanceof Illuminate\Contracts\Support\Jsonable) {
        $props[$key] = $prop->toJson();
    }
    if($prop === NULL) {
        $props[$key] = "'NULL'";
    }
}
@endphp

<{{ $component }}
    @foreach($props as $key => $prop)
        @if(is_bool($prop))
            @if ($prop)
                :{{$key}}=true
            @else
                :{{$key}}=false
            @endif
        @else
            :{{$key}}="{{$prop}}"
        @endif
    @endforeach/>