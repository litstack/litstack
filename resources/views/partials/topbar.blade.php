<nav class="fj-topbar justify-content-between align-items-center">
    <div>
        <a href="{{route('fjord.dashboard')}}" class="fjord-brand">
            <img src="{{ route('fjord.logo') }}" class="img-fluid" alt="">
        </a>
        {{-- <input class="form-control fjord-search" type="text" placeholder="Search everything">
        --}}
    </div>
    @guest
    @else
    <div>

        @if (Route::has('fjord.user-roles') && Route::has('fjord.role-permissions'))
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

                    @can('read user-roles')
                    <a class="dropdown-item"
                       href="{{route('fjord.user-roles')}}">
                        <i class="fas fa-address-card"></i> Roles
                    </a>
                    @endcan
                    @can('read role-permissions')
                    <a class="dropdown-item"
                       href="{{route('fjord.role-permissions')}}">
                        <i class="fas fa-key"></i> Permissions
                    </a>
                    @endcan
                </div>
            </div>
        @endif

        @foreach(fjord()->getNavigation('topbar') as $entry)
            <a
                class="fj-topbar_link mr-4"
                href="/{{ config('fjord.route_prefix') }}/{{ $entry['link'] }}">
                {!! $entry['icon'] !!}
            </a>
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
