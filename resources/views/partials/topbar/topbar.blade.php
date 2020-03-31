<nav class="fj-topbar justify-content-between align-items-center">
    <div>
        <a href="{{route('fjord.dashboard')}}" class="fjord-brand">
            <img src="{{ route('fjord.logo') }}" class="img-fluid" alt="">
        </a>
    </div>
    <div>
        <div class="fjord-hide" id="fjord-topbar-right">
            @auth('fjord')
                @include('fjord::partials.topbar.navigation')
                @include('fjord::partials.topbar.logout')
            @endauth
        </div>
    </div>
</nav>
