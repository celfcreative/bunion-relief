<?php
global $wp_query;

?>

<div class="blog-box">
    <div class="blogs row gap-5" data-page="<?= get_query_var('paged') ? get_query_var('paged') : 1 ?>"
        data-max="<?= $wp_query->max_num_pages ?>">
        @if ($blogs->have_posts())
            @while ($blogs->have_posts())
                @php $blogs->the_post() @endphp
                <x-card title="{!! get_the_title() !!}" description="{!! get_the_excerpt() !!}"
                    image="{!! get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'img-fluid']) !!}" link="{!! get_permalink() !!}" />
            @endwhile
            @php wp_reset_query() @endphp
        @endif
    </div>

    <button data-total="" class="btn btn-primary loading load-more" id="load-more">
        <label>Load more</label>
        <span class="loader"></span>
    </button>
</div>
<InnerBlocks />
