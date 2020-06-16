<nav class="fj-topbar justify-content-between align-items-center">
    <div>
        <a href="{{fjord()->url('/')}}" class="fj-brand">
            <img src="{{ route('fjord.logo') }}" class="img-fluid" alt="">
        </a>
    </div>
    <div>
        <div class="fj-hide" id="fj-topbar-right">
        
            @auth('fjord')
                @include('fjord::partials.topbar.navigation')
            @endauth
            
        </div>
    </div>
</nav>
