<div>
    @if ($surgeonImage)
        <div class="store-single-content-img-box d-flex flex-column gap-2 mb-3">
            {!! $surgeonImage !!}
        </div>
    @endif

    <div class="store-single-buttons w-100 mx-auto">
        @if ($surgeonPhone)
            <a href="tel:{{ $surgeonPhone }}"
                class="btn btn-primary border-dark-subtle store-single-button shadow surgeon-phone fs-7"
                data-dr-phone="{!! $surgeonName !!}">{{ $surgeonPhone }}</a>
        @endif
        @if ($surgeonURL)
            <a href="{{ $surgeonURL }}" class="btn btn-primary border-dark-subtle store-single-button shadow fs-7">View
                website</a>
        @endif
        <button type="button" class="btn btn-primary store-single-button btnStoreSingle shadow fs-7"
            data-title="{!! $surgeonName !!}" data-bs-toggle="modal" data-bs-target="#iTouchModal">Get in
            Touch</button>
    </div>
</div>

<div class="w-100">
    <h2 class="store-single-title text-primary fw-semibold">{!! $surgeonName !!}</h2>
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
    <div class="store-single-description mb-4 fw-light">
        @if ($surgeonDescription)
            {!! $surgeonDescription !!}
        @endif
    </div>

    <div class="">
        <p class="text-primary fw-medium mb-1">Address</p>
        <p class="mb-1 fw-light">{{ $surgeonAddress1 }} {{ $surgeonAddress2 }}</p>
        <p class="mb-1 fw-light">{{ $surgeonCity }}, {{ $surgeonZip }}</p>
        <p class="mb-1 fw-light">{{ $surgeonState }}, {{ $surgeonCountry }}</p>
        <p class='text-primary fw-light d-inline-block locate-icon'>
            <i class='bi bi-geo-alt'></i>
        </p>
        <p class="distanceFrom text-primary fw-light d-inline-block"></p>
    </div>
</div>
