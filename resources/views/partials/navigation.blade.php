@auth('lit')
    <nav class="fj-navigation">

        @include('lit::partials.nav_loader')

        <fj-main-navigation :items="{{collect(lit()->config('navigation')->main)}}"></fj-navigation>
    </nav>
@endauth
