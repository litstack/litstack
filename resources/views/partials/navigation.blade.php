@auth(config('lit.guard'))
    <nav class="lit-navigation">

        @include('litstack::partials.nav_loader')

        
        <lit-main-navigation :items="{{collect(lit()->config('navigation')->main)}}"></lit-navigation>
    </nav>
@endauth
