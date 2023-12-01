<div class="{{ $block->classes }} pb-6">
    @if ($citations)
        <div class="citation container p-0">
            <div class="accordion accordion-flush" id="accordion-{{ $id }}">
                @foreach ($citations as $item)
                    <div class="accordion-item border-0">
                        <h3 class="accordion-button citation__info collapsed px-0 text-decoration-none border-bottom border-secondary bg-white fw-semibold"
                            type="button" data-bs-toggle="collapse" data-bs-target="#citation-{{ $item->ID }}"
                            aria-expanded="false" aria-controls="flush-collapseOne">
                            {{ $item->post_title }}
                        </h3>
                        <div id="citation-{{ $item->ID }}" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingOne" data-bs-parent="#accordion-{{ $id }}">
                            <div class="accordion-body py-0 ps-0">
                                <span class="question__answer fw-light ">{!! $item->post_content !!}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
