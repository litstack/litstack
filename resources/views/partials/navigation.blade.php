@auth('fjord')
<nav class="fj-navigation">
    <fj-navigation :items="{{fjord()->getNavigation()}}"></fj-navigation>
</nav>
@endauth
