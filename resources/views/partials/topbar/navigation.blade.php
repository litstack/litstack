<b-dropdown class="dropdown-sm-square" dropbottom no-caret variant="transparent" size="md">
    <template v-slot:button-content>
        <svg viewBox="0 0 19 15" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2"><path d="M0 2h19V0H0v2zM0 8.5h19v-2H0v2zM0 15h19v-2H0v2z" fill="#fff"/></svg>
    </template>
    
    @php
    $first = true;
    @endphp
    @foreach(lit()->config('navigation')->topbar as $section)
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
                            <div class="mr-2 d-inline-block lit-topbar__icon">{!! $entry['icon'] !!}</div>
                        @endif
                         {!! $entry['title'] !!}
                    </b-dropdown-item>
                @endisset
                @isset ($entry['component'])
                    <{{ $entry['component'] }}></{{ $entry['component'] }}>
                @endisset
            @endif

        @endforeach
    @endforeach
    <b-dropdown-divider></b-dropdown-divider>
    @if (in_array(\Ignite\Info\InfoServiceProvider::class, config('lit.providers')))
        <b-dropdown-item href="{{route('lit.info')}}">
            <div class="mr-2 d-inline-block lit-topbar__icon">{!! fa('info') !!}</div>
            System Info
        </b-dropdown-item>
    @endif
    <lit-logout :url="'{{route('lit.logout')}}'"></lit-logout>
    
</b-dropdown>