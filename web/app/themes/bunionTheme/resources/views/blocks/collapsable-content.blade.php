<div class="{{ $block->classes }}">
    <p class="d-inline-flex gap-1">
        <a class="collapse-content fw-semibold" data-bs-toggle="collapse" href="#collapseContent" role="button"
            aria-expanded="false" aria-controls="collapseContent">Read more</a>
    </p>
    <div class="collapse" id="collapseContent">
        <div class="">
            <InnerBlocks />
        </div>
    </div>
</div>
