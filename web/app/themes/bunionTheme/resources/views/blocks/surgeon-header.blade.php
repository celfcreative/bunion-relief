@if ($surgeonImage)
    <div
        class="{{ $block->classes }} store-single-content d-flex align-items-center gap-md-6 gap-4 my-4 my-md-6 flex-column flex-lg-row">
        <div>
            @if ($surgeonImage)
                <div class="store-single-content-img-box d-flex flex-column gap-2 mb-3">
                    {!! $surgeonImage !!}
                </div>
            @endif

            <div class="store-single-buttons w-100 mx-auto">
                <button type="button" class="btn btn-primary store-single-button btnStoreSingle shadow fs-7"
                    data-title="{{ $surgeonName }}" data-bs-toggle="modal" data-bs-target="#iTouchModal">Get In
                    Touch</button>
                @if ($surgeonPhone)
                    <a href="tel:{{ $surgeonPhone }}"
                        class="btn btn-light border-dark-subtle store-single-button shadow surgeon-phone fs-7">{{ $surgeonPhone }}</a>
                @endif
                @if ($surgeonURL)
                    <a href="{{ $surgeonURL }}"
                        class="btn btn-light border-dark-subtle store-single-button shadow fs-7">View
                        Website</a>
                @endif
            </div>
        </div>

        <div class="w-100">
            <h2 class="store-single-title text-primary fw-semibold">{{ $surgeonName }}</h2>
            @if ($surgeonCategory)
                @foreach ($surgeonCategory as $key => $category)
                    <p class="h3 fw-light mb-1 d-sm-inline-block d-block">
                        {{ $category->name }}
                        @if (!$loop->last)
                            ,
                        @endif
                    </p>
                @endforeach
            @endif
            <div style="height:2px" aria-hidden="true" class="w-100 bg-secondary my-3 opacity-75"></div>
            <div class="store-single-description mb-4">
                <InnerBlocks />
            </div>

            <div class="">
                <p class="text-primary fw-medium mb-1">Address</p>
                <p class="mb-1">{{ $surgeonAddress1 }} {{ $surgeonAddress2 }}</p>
                <p class="mb-1">{{ $surgeonCity }}, {{ $surgeonZip }}</p>
                <p class="mb-1">{{ $surgeonState }}, {{ $surgeonCountry }}</p>
                <p class='text-primary fw-light d-inline-block locate-icon'>
                    <i class='bi bi-geo-alt'></i>
                </p>
                <p class="distanceFrom text-primary fw-light d-inline-block"></p>

            </div>

        </div>
    </div>
@else
    <div
        class="{{ $block->classes }} store-single-content d-flex align-items-start gap-md-6 gap-4 my-4 my-md-6 flex-column">
        <div>
            @if ($surgeonImage)
                <div class="store-single-content-img-box d-flex flex-column gap-2 mb-3">
                    {!! $surgeonImage !!}
                </div>
            @endif
        </div>

        <div class="w-100">
            <div>
                <h2 class="store-single-title text-primary fw-semibold">{{ $surgeonName }}</h2>
                @if ($surgeonCategory)
                    @foreach ($surgeonCategory as $category)
                        <p class="h3 fw-light d-sm-inline-block d-block mb-0">
                            {{ $category->name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        </p>
                    @endforeach
                    <div class="d-flex flex-column flex-md-row justify-content-between w-100 my-4 gap-3">
                        <button type="button" class="btn btn-primary store-single-button btnStoreSingle shadow fs-7"
                            data-title="{{ $surgeonName }}" data-bs-toggle="modal" data-bs-target="#iTouchModal">Get
                            In
                            Touch</button>
                        <div class="d-flex flex-column flex-md-row gap-3">
                            @if ($surgeonPhone)
                                <a href="tel:{{ $surgeonPhone }}"
                                    class="btn btn-light border-dark-subtle store-single-button shadow surgeon-phone fs-7">{{ $surgeonPhone }}</a>
                            @endif
                            @if ($surgeonURL)
                                <a href="{{ $surgeonURL }}"
                                    class="btn btn-light border-dark-subtle store-single-button shadow fs-7">View
                                    Website</a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div style="height:2px" aria-hidden="true" class="w-100 bg-secondary my-3 opacity-75"></div>
            <div class="store-single-description mb-4">
                <InnerBlocks />
            </div>

            <div class="">
                <p class="text-primary fw-medium mb-1">Address</p>
                <p class="mb-1">{{ $surgeonAddress1 }} {{ $surgeonAddress2 }}</p>
                <p class="mb-1">{{ $surgeonCity }}, {{ $surgeonZip }}</p>
                <p class="mb-1">{{ $surgeonState }}, {{ $surgeonCountry }}</p>
                <p class='text-primary fw-light d-inline-block locate-icon'>
                    <i class='bi bi-geo-alt'></i>
                </p>
                <p class="distanceFrom text-primary fw-light d-inline-block"></p>
            </div>

        </div>
    </div>
@endif

<div class="modal fade " id="iTouchModal" tabindex="-1" aria-labelledby="iTouchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 px-4 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-img-box row align-items-center w-100 m-auto px-3">
                <div class="col-md-8 col-12">
                    <h1 class="modal-title text-primary fw-semibold fs-2 px-1 fw-semibold" id="iTouchModalLabel">Contact
                        Us
                    </h1>
                    <p class="modal-description ps-2 pt-0 mb-0  fw-light">{{ $formDescription }}</p>
                </div>
                <div class="col-4">
                    {{ $formIcon }}
                </div>
            </div>

            <div class="modal-body pt-0">
                @if (!$block->preview)
                    @php(advanced_form('form_get_in_touch'))
                @endif
            </div>
        </div>
    </div>
</div>
