@auth('fjord')
    <nav class="fj-navigation">

        @include('fjord::partials.nav_loader')

        <fj-main-navigation :items="{{collect(fjord()->config('navigation')->main)}}"></fj-navigation>
    </nav>
@endauth
