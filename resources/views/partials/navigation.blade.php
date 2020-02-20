@auth('fjord')
<nav class="fj-navigation">
    <fj-navigation>
        @foreach (fjord()->getNavigation() as $entry)
            @include('fjord::partials.navitem')
        @endforeach
    </fj-navigation>
</nav>
@endauth
