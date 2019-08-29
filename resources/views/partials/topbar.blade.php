<nav class="fjord-topbar justify-content-between align-items-center">
    <div>
        <a href="#" class="fjord-brand">
            <img src="{{asset('fjord/images/fjord-logo.png')}}" class="img-fluid" alt="">
        </a>
        @guest
        @else
            <input class="form-control fjord-search" type="text" placeholder="Search everything">
        @endguest
    </div>

    @guest

    @else
        <div>
            <a class="fjord-topbar_link mr-4" href="{{route('fjord.users')}}">
                <i class="fas fa-user-friends"></i>
            </a>
            @foreach(fjord()->getNavigation('topbar') as $entry)
                <a class="fjord-topbar_link mr-4" href="/{{ config('fjord.route_prefix') }}/{{ $entry['link'] }}">
                    {!! $entry['icon'] !!}
                </a>
            @endforeach


            <a class="fjord-topbar_link" href="{{route('fjord.logout')}}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                logout <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{route('fjord.logout')}}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    @endguest
</nav>
