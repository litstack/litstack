@foreach($repeatables as $rep)
    @if($rep->hasView())
        @include($rep->view, [
            'data' => $rep            
        ])
    {{-- @elseif($rep->hasX())
        <x-rep-image :rep="$rep"/> --}}
    @endif
@endforeach