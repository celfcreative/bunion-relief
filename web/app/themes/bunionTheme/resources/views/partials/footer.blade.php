<div class="footer container">
    <div class="row align-items-center gap-3 gap-md-5">
        <div class="footer__brands col-lg-2 mb-2 mb-lg-0 px-0 pt-4 pt-md-0">
            <div class="d-flex flex-column gap-3">
                <img src="@asset('images/br-logo-white.png')" alt="bunion relief white logo" class="brand-img w-100 ">
                <img src="@asset('images/br-phantom-logo-white.png')" alt="bunion relief mis logo" class="brand-img w-100">
            </div>
        </div>

        @if ($footer)
            <div class="footer__content col p-0">
                <div
                    class="footer__info row mb-3 ms-lg-6 gap-3 gap-md-2 flex-column flex-md-row justify-content-between">
                    @foreach ($footer as $item)
                        <div class="footer__list col-auto">
                            <h3 class="text-capitalize text-white fs-5">{{ $item->label }}</h3>

                            @foreach ($item->children as $child)
                                <ul class="nav flex-column">
                                    <li class="nav-item"><a href="{{ $child->url }}"
                                            class="nav-link text-capitalize text-white fw-light ps-0 py-0 fs-6">{{ $child->label }}</a>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="footer__list col-auto siteOptions">
                        <h3 class="text-capitalize text-white fs-5">contact us</h3>
                        <ul class="nav flex-column">
                            <p class="text-white my-0 fw-light fs-6">{!! $address !!}</p>
                            <p class="text-white my-0 fw-light fs-6">{!! $phoneNum !!}</p>
                        </ul>
                        <ul class="nav gap-3 mt-2">
                            @if ($socialFB)
                                <li class="nav-link p-0"><a href="{{ $socialFB }}"
                                        class="icon icon-link icon-link-hover" target="_blank"
                                        style="--bs-icon-link-transform:translate3d(0, -.125rem, 0)"><i
                                            class="bi bi-facebook text-white"></i></a>
                                </li>
                            @endif
                            @if ($socialIG)
                                <li class="nav-link p-0"><a href="{{ $socialIG }}" class="icon-link icon-link-hover"
                                        target="_blank" style="--bs-icon-link-transform:translate3d(0, -.125rem, 0)"><i
                                            class="bi bi-instagram text-white"></i></a>
                                </li>
                            @endif
                            @if ($socialX)
                                <li class="nav-link p-0"><a href="{{ $socialX }}" class="icon-link icon-link-hover"
                                        target="_blank" style="--bs-icon-link-transform:translate3d(0, -.125rem, 0)"><i
                                            class="bi bi-twitter-x text-white"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="footer__buttons ms-lg-5 d-flex flex-column flex-md-row align-items-lg-center">
                    @if ($getRepeaterButtons)
                        @foreach ($getRepeaterButtons as $button)
                            <a href="{{ $button['footer_button_link'] }}"
                                class="btn footer__button bg-white text-capitalize me-0 me-md-2 mb-3 mb-lg-0 ms-md-0 fs-8">{!! $button['footer_button'] !!}</a>
                        @endforeach
                    @endif
                    @if ($currentPage == $healthPage)
                        <a href="{{ get_field('provider_button_link', 'option') }}"
                            class="btn footer__button bg-white text-capitalize me-0 me-md-2 mb-3 mb-lg-0 ms-md-0 fs-8">{{ get_field('provider_button', 'option') }}</a>
                    @endif
                    @if ($productPage)
                        <a href="{{ get_field('provider_button_link', 'option') }}"
                            class="btn footer__button bg-white text-capitalize me-0 me-md-2 mb-3 mb-lg-0 ms-md-0 fs-8">{{ get_field('provider_button', 'option') }}</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
