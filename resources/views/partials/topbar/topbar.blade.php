<nav class="lit-topbar justify-content-between align-items-center">
    <div>
        <a href="{{lit()->url('/')}}" class="lit-brand">
            @include('litstack::partials.logo')
            {{-- <img src="{{ route('lit.logo') }}" class="img-fluid" alt=""> --}}
        </a>
    </div>
    <div>
        <div class="lit-hide" id="lit-topbar-right">
        
            @auth('lit')
                @include('litstack::partials.topbar.navigation')
            @endauth
            
        </div>
    </div>
</nav>
