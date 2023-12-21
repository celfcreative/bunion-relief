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
                @if ($surgeonPhone)
                    <a href="tel:{{ $surgeonPhone }}"
                        class="btn btn-primary border-dark-subtle store-single-button shadow surgeon-phone fs-7"
                        data-dr-phone='{{ $surgeonName }}'>{{ $surgeonPhone }}</a>
                @endif
                <div class="d-flex flex-column flex-md-row gap-3">
                    @if ($surgeonURL)
                        <a href="{{ $surgeonURL }}"
                            class="btn btn-primary border-dark-subtle store-single-button shadow fs-7">View
                            website</a>
                    @endif

                    <button type="button" class="btn btn-primary store-single-button btnStoreSingle shadow fs-7"
                        data-title="{{ $surgeonName }}" data-bs-toggle="modal" data-bs-target="#iTouchModal">Get
                        in
                        Touch</button>
                </div>
            </div>
        @endif
    </div>
    <div style="height:2px" aria-hidden="true" class="w-100 bg-secondary my-3 opacity-75"></div>
    <div class="store-single-description mb-4">
        @if ($surgeonDescription)
            {!! $surgeonDescription !!}
        @endif
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
