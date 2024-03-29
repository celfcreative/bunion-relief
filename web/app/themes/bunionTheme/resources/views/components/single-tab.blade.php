<!-- Tab panes -->
<div class="tab">
    <div class="row justify-content-center align-items-start w-100 m-0">
        @if ($tabImage)
            <div class="tab-img-box col-lg-4 col-md-6 p-3 px-4 ps-2">
                <img src="{{ $tabImage }}" alt="tab image 1" class="tab-img w-100 rounded p-1">
            </div>
        @endif
        <div class="tab-body col-lg-8 col-md-6 pt-0 pt-sm-4">
            <h3 class="text-primary fw-bold my-3 mt-0 mt-md-3">{{ $tabTitle }}</h3>
            <span class="fw-light mb-4 ">{!! $tabDescription !!}</span>
            @if ($hasButton && $tabButton)
                <div class="wp-block-button is-style-primary  mb-4">
                    <a href="{{ get_the_permalink($tabButton->ID) }}"
                        class="wp-block-button__link bg-primary text-white">{{ $tabButton->post_title }}</a>
                </div>
            @endif
        </div>
    </div>

</div>
