<li
    @isset($entry['link'])
        class="nav-{{$entry['link']}}"
    @endisset
    @isset($entry['children'])
        class="@foreach ($entry['children'] as $child)@isset($child['link']) nav-{{$child['link']}}@endisset @endforeach"
    @endisset
    >
    @isset($entry['children'])
        <a href="#" class="aw-sidebar__has-children">
            <div class="aw-sidebar__icon">
                {!!$entry['icon']!!}
            </div>
            {{$entry['title']}}
            <i class="fas fa-caret-down text-muted aw-sidebar__down"></i>
        </a>
        <ul>
            @foreach ($entry['children'] as $entry)
                @include('fjord::partials.navitem')
            @endforeach
        </ul>

    @else
        <a href="/admin/{{$entry['link']}}">
            @isset($entry['icon'])
                <div class="aw-sidebar__icon">
                    {!!$entry['icon']!!}
                </div>
            @endisset
            {{$entry['title']}}
        </a>
    @endisset
</li>
