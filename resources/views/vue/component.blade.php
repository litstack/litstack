<{{ $component }}
    @foreach($props as $key => $prop)
        @if(is_string($prop))
            @php
                $prop="'".$prop."'";
            @endphp
        @endif
        @if(is_array($prop))
            @php
                $prop=collect($prop);
            @endphp
        @endif

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