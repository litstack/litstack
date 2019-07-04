@guest

@else
<nav class="aw-sidebar">
    <navigation>
        @foreach (config('fjord-navigation') as $entry)
            @include('fjord::partials.navitem')
        @endforeach
    </navigation>
</nav>
@endguest
