@auth('fjord')
    <nav class="fj-navigation">
        <fj-navigation :items="{{collect(fjord()->app()->config('navigation.main'))}}"></fj-navigation>
    </nav>
@endauth
