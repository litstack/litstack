<b-dropdown class="m-md-2" right variant="transparent" size="sm">
    <template v-slot:button-content>
        <fa-icon icon="cogs" />
    </template>
    
    @php
    $first = true;
    @endphp
    @foreach(fjord()->getNavigation('topbar') as $group)
        @if($first)
            @php
            $first = false;
            @endphp
        @else
            <b-dropdown-divider></b-dropdown-divider>
        @endif
        @foreach($group as $entry)
            @if(is_string($entry))
                <header role="heading" class="dropdown-header">{{ $entry }}</header>
            @else

                @isset($entry['link'])
                    <b-dropdown-item href="/{{ config('fjord.route_prefix') }}/{{ $entry['link'] }}">
                        @isset($entry['text'])
                            {{ $entry['text'] }}
                        @else
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