@guest

@else
<nav class="fjord-navigation">
    <div class="container">
        <h5 class="mt-3 mb-4 text-muted">
            @yield('title')
        </h5>
    </div>
    <navigation>
        @foreach (config('fjord-navigation') as $entry)
            @include('fjord::partials.navitem')
        @endforeach
    </navigation>
</nav>
@endguest
