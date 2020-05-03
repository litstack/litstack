<div class="ph-item fj-nav-loader">
    <div class="ph-col-12">
        <div class="ph-row">

            @foreach(Fjord::config('navigation')->main as $section)
                @foreach($section as $item)
                    @if($item['type'] == 'title')
                            <div class="ph-col-{{ ph_cols($item['title']) }} fj-ph-title"></div>
                            <div class="ph-col-{{ 12 - ph_cols($item['title']) }} empty fj-ph-title"></div>
                    @endif

                    @if($item['type'] == 'group' || $item['type'] == 'entry')
                        <div class="ph-col-1 fj-ph-entry"></div>
                        <div class="ph-col-1 empty fj-ph-entry"></div>
                        <div class="ph-col-{{ ph_cols($item['title'], 10) }} fj-ph-entry"></div>
                        <div class="ph-col-{{ 10 - ph_cols($item['title'], 10) }} empty fj-ph-entry"></div>
                    @endif
                @endforeach

                <div class="ph-col-12 empty fj-ph-divider"></div>

            @endforeach
        </div>
    </div>
</div>