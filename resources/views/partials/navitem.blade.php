<li
    @isset($entry['link'])
        class="nav-{{str_replace('/', '-', $entry['link'])}}"
    @endisset
    @isset($entry['children'])
        class="@foreach ($entry['children'] as $child)@isset($child['link']) nav-{{$child['link']}}@endisset @endforeach"
    @endisset
    >
    @isset($entry['children'])
        <a href="#" class="fjord-navigation__has-children">
            <div class="fjord-navigation__icon">
                {!!$entry['icon']!!}
            </div>
            {{$entry['title']}}
            <i class="fas fa-caret-down text-muted fjord-navigation__down"></i>
        </a>
        <ul>
            @foreach ($entry['children'] as $entry)
                @include('fjord::partials.navitem')
            @endforeach
        </ul>

    @else
        <a href="/admin/{{$entry['link']}}">
            @isset($entry['icon'])
                <div class="fjord-navigation__icon">
                    {!!$entry['icon']!!}
                </div>
            @endisset
            {{$entry['title']}}
        </a>
    @endisset
</li>
