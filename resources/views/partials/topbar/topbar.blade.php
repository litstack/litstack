<nav class="fj-topbar justify-content-between align-items-center">
    <div>
        <a href="{{lit()->url('/')}}" class="fj-brand">
            <img src="{{ route('lit.logo') }}" class="img-fluid" alt="">
        </a>
    </div>
    <div>
        <div class="fj-hide" id="fj-topbar-right">
        
            @auth('lit')
                @include('lit::partials.topbar.navigation')
            @endauth
            
        </div>
    </div>
</nav>
