@auth('fjord')
    <nav class="fj-navigation">
        <fj-navigation :items="{{collect(fjord()->config('navigation')->main)}}"></fj-navigation>
    </nav>
@endauth
