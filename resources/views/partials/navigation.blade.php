@auth('fjord')
    <nav class="fj-navigation">
        <fj-main-navigation :items="{{collect(fjord()->config('navigation')->main)}}"></fj-navigation>
    </nav>
@endauth
