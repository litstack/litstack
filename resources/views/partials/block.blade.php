
@foreach($repeatables as $rep)
    @if($rep->hasView())
        {!! $rep->view->with('rep', $rep) !!}
    @elseif($rep->hasX())
        {{-- 
            The [?? Dummy::class] is required to make the blade compiler think 
            it is compiling a component class. 
        --}}
        @component($rep->getXClass() ?? Dummy::class, $rep->getX(), ['rep' => $rep])
        @endcomponentClass
        
    @endif
@endforeach