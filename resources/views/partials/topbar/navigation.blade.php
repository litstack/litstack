<b-dropdown class="m-md-2" right variant="transparent" size="sm">
    <template v-slot:button-content>
        <fa-icon icon="cogs" />
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
                        @isset($entry['title'])
                            {{ $entry['title'] }}
                        @elseif(isset($entry['icon']))
                            {!! $entry['icon'] !!} {{ $entry['link'] }}
                        @endisset
                    </b-dropdown-item>
                @endisset
                @isset ($entry['component'])
                    <{{ $entry['component'] }}></{{ $entry['component'] }}>
                @endisset
            @endif

        @endforeach
    @endforeach
    <b-dropdown-divider></b-dropdown-divider>
    <fj-locales />
</b-dropdown>