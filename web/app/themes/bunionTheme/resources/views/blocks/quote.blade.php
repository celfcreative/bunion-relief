@if ($quotes)
    <div class="carousel slide {{ $block->classes }} mb-5" id="carouselIndicator">
        <div class="carousel-indicators">
            @if (count($quotes) > 1)
                @foreach ($quotes as $quote => $value)
                    <button type="button" data-bs-target="#carouselIndicator" data-bs-slide-to="{{ $quote }}"
                        class="{{ $quote == 0 ? 'active' : '' }} carouselBtn"
                        aria-current="{{ $quote == 0 ? 'true' : 'false' }}"></button>
                @endforeach
            @endif
        </div>

        <div class="quotes carousel-inner">
            @foreach ($quotes as $quote => $value)
                <div class="carousel-item {{ $quote == 0 ? 'active' : '' }}">
                    <x-quote-slide :id="$value->ID" />
                </div>
            @endforeach
        </div>
    </div>

@endif
