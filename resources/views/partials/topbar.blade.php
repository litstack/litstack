<nav class="fj-topbar justify-content-between align-items-center">
    <div>
        <a href="{{route('fjord.dashboard')}}" class="fjord-brand">
            <img src="{{ route('fjord.logo') }}" class="img-fluid" alt="">
        </a>
    </div>
    @auth('fjord')
        <div>

            <b-dropdown class="m-md-2" right variant="transparent" size="sm">
                <template v-slot:button-content>
                  <fa-icon icon="cogs" />
                </template>
                @if (Route::has('fjord.user-roles') && Route::has('fjord.role-permissions'))
                    @can('read user-roles')
                        <b-dropdown-item href="{{route('fjord.user-roles')}}">Roles</b-dropdown-item>
                    @endcan
                    @can('read role-permissions')
                        <b-dropdown-item href="{{route('fjord.role-permissions')}}">Permissions</b-dropdown-item>
                    @endcan
                    <b-dropdown-divider></b-dropdown-divider>
                @endif
                @foreach(fjord()->getNavigation('topbar') as $entry)
                    <b-dropdown-item href="/{{ config('fjord.route_prefix') }}/{{ $entry['link'] }}">
                        @isset($entry['text'])
                            {{ $entry['text'] }}
                        @else
                            {!! $entry['icon'] !!} {{ $entry['link'] }}
                        @endisset
                    </b-dropdown-item>
                @endforeach
                <b-dropdown-divider></b-dropdown-divider>
                <fj-locales />
            </b-dropdown>

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
    @endauth
</nav>
