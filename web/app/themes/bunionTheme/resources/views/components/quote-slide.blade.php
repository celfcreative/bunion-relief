<div class="quote d-flex flex-lg-row flex-column align-items-center justify-content-center gap-3 gap-lg-0">
    @if ($image)
        <div class="quote-img-box col-4 col-lg-2">
            <img src="{{ $image }}" alt="Doctor's Image" class="quote-img w-100 rounded">
        </div>
    @endif
    <div class="quote-content col-12 col-lg-10 px-0 px-lg-5">
        <p class="fs-4 fw-light lh-base mb-4">{{ $quote }}</p>
        <h3 class="heading fs-6 fw-medium">{{ $name }}</h3>
        <span class="p-0 fw-lighter d-block fs-6">{{ $location }}</span>
        <span class="p-0 fw-lighter fs-6">{{ $locationline2 }}</span>
    </div>
</div>
