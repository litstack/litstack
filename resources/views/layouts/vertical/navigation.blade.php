@guest

@else
<nav class="fjord-navigation">
    <navigation>
        @foreach (fjord()->getNavigation() as $entry)
            @include(fjord_view('navitem', true))
        @endforeach
    </navigation>
</nav>
@endguest
