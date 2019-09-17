{{-- Check for crud routes and verify permission --}}
@php
    $show = false;
@endphp

@if(! array_key_exists('link', $entry))

    @php
    $show = true;
    @endphp

@elseif (strpos($entry['link'], '/') === false)
    {{-- this is a crud route --}}
    @can('read ' . ($entry['link'] ?? ''))
        @php
            $show = true;
        @endphp
    @else
        {{-- show if class doesn't use permissions --}}
        @php
            $show = !hasClassPermissions($entry['link']);
        @endphp
    @endcan

@else

    {{-- this is NOT a crud route, check, if roles are set --}}
    @if (array_key_exists('role', $entry))
        @php
            $roles = implode($entry['role'], '|');
        @endphp
        @hasanyrole($roles)
            @php
                $show = true;
            @endphp
        @endhasanyrole
    @else
        @php
            $show = true;
        @endphp
    @endif
@endif

@if ($show)
    <li
        @isset($entry['link'])
            class="nav-{{str_replace('/', '-', $entry['link'])}}"
        @endisset
        @isset($entry['children'])
            class="@foreach ($entry['children'] as $child)@isset($child['link']) nav-{{str_replace('/', '-', $child['link'])}}@endisset @endforeach"
        @endisset
        >
        @isset($entry['children'])
            <a href="#" class="fj-navigation__has-children">
                <div class="fj-navigation__icon">
                    {!!$entry['icon']!!}
                </div>
                {{$entry['title']}}
                <i class="fas fa-caret-down text-muted fj-navigation__down"></i>
            </a>
            <ul>
                @foreach ($entry['children'] as $entry)
                    @include('fjord::partials.navitem')
                @endforeach
            </ul>

        @else
            <a href="/{{ config('fjord.route_prefix') }}/{{$entry['link']}}">
                @isset($entry['icon'])
                    <div class="fj-navigation__icon">
                        {!!$entry['icon']!!}
                    </div>
                @endisset
                {{$entry['title']}}
            </a>
        @endisset
    </li>
@endif
