<nav class="fjord-topbar justify-content-between align-items-center">
    <div>
        <a href="#" class="fjord-brand">
            <img src="{{asset('fjord/images/fjord-logo.png')}}" class="img-fluid" alt="">
        </a>
        <input class="form-control form-control-lg fjord-search" type="text" placeholder="Search everything">
    </div>

    @guest

    @else
        <a class="fjord-topbar_link" href="{{route('fjord.logout')}}"
           onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
            logout <i class="fas fa-sign-out-alt"></i>
        </a>
        <form id="logout-form" action="{{route('fjord.logout')}}" method="POST" style="display: none;">
            @csrf
        </form>
    @endguest
</nav>
