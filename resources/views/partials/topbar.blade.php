<nav class="fj-topbar justify-content-between align-items-center">
    <div>
        <a href="#"
           class="fjord-brand">
            <img src="{{asset('fjord/images/fjord-logo.png')}}"
                 class="img-fluid"
                 alt="">
        </a>
        {{-- <input class="form-control fjord-search" type="text" placeholder="Search everything">
        --}}
    </div>
    @guest
    @else
    <div>

        <div class="btn-group mr-4">
            <button
                type="button"
                class="btn btn-sm btn-transparent dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <i class="fas fa-user-friends"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item"
                   href="{{route('fjord.user-roles')}}">
                    <i class="fas fa-user-tag mr-2"></i> User-Roles
                </a>
                <a class="dropdown-item"
                   href="{{route('fjord.role-permissions')}}">
                    <i class="fas fa-user-tag mr-2"></i> Role-Permissions
                </a>
            </div>
        </div>
        @foreach(fjord()->getNavigation('topbar') as $entry) <a class="fj-topbar_link mr-4"
               href="/{{ config('fjord.route_prefix') }}/{{ $entry['link'] }}"> {!! $entry['icon'] !!} </a>
            @endforeach
            <a class="fj-topbar_link"
               href="{{route('fjord.logout')}}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();"> logout <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form"
                  action="{{route('fjord.logout')}}"
                  method="POST"
                  style="display: none;">
                @csrf
            </form>
    </div>
    @endguest
</nav>
