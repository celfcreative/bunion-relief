@if ($step)
    <div class="{{ $block->classes }}">
        <div class="stepper-container row align-items-center p-0 gap-4 gap-lg-0 flex-lg-row">
            <div class="swiper stepper-slider col-4">
                <div class="stepper swiper-wrapper">
                    @foreach ($step as $index => $single)
                        <div class="stepper-img-box col-md-4 col-6 swiper-slide w-100" data-hash="{{ $index }}">
                            @if (isset($single['step_image']['sizes']['500-image']))
                                <img src="{{ $single['step_image']['sizes']['500-image'] }}" alt="stepper image"
                                    class="stepper-img w-100 rounded">
                            @else
                                <div class="stepper-img w-100 rounded"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="stepper-body col-8 position-relative">
                <div class="stepper-info row align-items-center">
                    <div class="swiper-pagination" id="swiper-rotate"></div>
                    <div class="step">
                        <div class="stepper-step ">
                            @foreach ($step as $index => $single)
                                <div class="stepper-single d-flex align-items-center gap-6 opacity-50"
                                    data-image="{{ $index }}">
                                    <div class="stepper-content w-100">
                                        <a href="#{{ $index }}" class="text-decoration-none">
                                            <h3 class="stepper-title">{{ $single['step_title'] }}</h3>
                                            <p class="stepper-description text-black">{{ $single['step_description'] }}
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
