<div class="{{ $block->classes }}">
    @if ($stores)
    <div class="map-block-content">
      {!! do_shortcode('[wpsl_map id="'.implode(",", $stores).'"]') !!}
    </div>
    @endif
    {{-- // {!! do_shortcode('[wpsl_store='map']') !!} --}}
</div>
