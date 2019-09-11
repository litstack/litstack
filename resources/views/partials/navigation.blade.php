@guest

@else
<nav class="fj-navigation">
    <navigation>
        @foreach (fjord()->getNavigation() as $entry)
            @include('fjord::partials.navitem')
        @endforeach
    </navigation>
</nav>
@endguest
