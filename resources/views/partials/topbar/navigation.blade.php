<b-dropdown class="m-md-2" dropbottom no-caret variant="transparent" size="sm">
    <template v-slot:button-content>
        <fa-icon icon="bars" />
    </template>
    
    @php
    $first = true;
    @endphp
    @foreach(fjord()->config('navigation')->topbar as $section)
        @if($first)
            @php
            $first = false;
            @endphp
        @else
            <b-dropdown-divider></b-dropdown-divider>
        @endif
        @foreach($section as $entry)
            @if($entry['type'] == 'title')
                <header role="heading" class="dropdown-header">{{ $entry['title'] }}</header>
            @else
                @isset($entry['link'])
                    <b-dropdown-item href="{{ $entry['link'] }}">
                        @if(array_key_exists('icon', $entry))
                            <div class="mr-3 d-inline-block">{!! $entry['icon'] !!}</div>
                        @endif
                         {{ $entry['title'] }}
                    </b-dropdown-item>
                @endisset
                @isset ($entry['component'])
                    <{{ $entry['component'] }}></{{ $entry['component'] }}>
                @endisset
            @endif

        @endforeach
    @endforeach
    @if(config('fjord.translatable.translatable'))
        <b-dropdown-divider></b-dropdown-divider>
        <fj-locales :config={{ json_encode(config('fjord.translatable')) }}></fj-locales>
    @endif
    <b-dropdown-divider></b-dropdown-divider>
    <fj-logout :url="'{{route('fjord.logout')}}'"></fj-logout>
    
</b-dropdown>