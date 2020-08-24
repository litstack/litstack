<div class="ph-item lit-nav-loader">
    <div class="ph-col-12">
        <div class="ph-row">

            @foreach(Lit::config('navigation')->main as $section)
                @foreach($section as $item)
                    @if($item['type'] == 'title')
                            <div class="ph-col-{{ ph_cols($item['title']) }} lit-ph-title"></div>
                            <div class="ph-col-{{ 12 - ph_cols($item['title']) }} empty lit-ph-title"></div>
                    @endif

                    @if($item['type'] == 'group' || $item['type'] == 'entry')
                        <div class="ph-col-1 lit-ph-entry"></div>
                        <div class="ph-col-1 empty lit-ph-entry"></div>
                        <div class="ph-col-{{ ph_cols($item['title'], 10) }} lit-ph-entry"></div>
                        <div class="ph-col-{{ 10 - ph_cols($item['title'], 10) }} empty lit-ph-entry"></div>
                    @endif
                @endforeach

                <div class="ph-col-12 empty lit-ph-divider"></div>

            @endforeach
        </div>
    </div>
</div>