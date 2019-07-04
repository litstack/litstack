<nav class="aw-topbar justify-content-between align-items-center">
    <a href="#" class="aw-brand">
        <img src="{{asset('fjord/images/fjord-logo.png')}}" class="img-fluid" alt="">
    </a>

    @guest

    @else
        <a class="aw-topbar_link" href="{{route('fjord.logout')}}"
           onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
            logout <i class="fas fa-sign-out-alt"></i>
        </a>
        <form id="logout-form" action="{{route('fjord.logout')}}" method="POST" style="display: none;">
            @csrf
        </form>
    @endguest
</nav>
