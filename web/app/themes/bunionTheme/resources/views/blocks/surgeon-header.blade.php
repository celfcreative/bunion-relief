@if ($surgeonImage)
    <div
        class="{{ $block->classes }} store-single-content d-flex align-items-center gap-md-6 gap-4 my-4 my-md-6 flex-column flex-lg-row">

        @include('partials.surgeon-content')
    </div>
@else
    <div
        class="{{ $block->classes }} store-single-content d-flex align-items-start gap-md-6 gap-4 my-4 my-md-6 flex-column">
        
        @include('partials.surgeon-content-noimg')
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
                    <h1 class="modal-title text-primary fw-semibold fs-2 fw-semibold" id="iTouchModalLabel">Contact
                        Us
                    </h1>
                    <p class="modal-description mb-0 fw-light">{{ $formDescription }}</p>
                </div>
                <div class="col-4">
                    {{ $formIcon }}
                </div>
            </div>

            <div class="modal-body pt-0">
                @if (!$block->preview)
                    @php(advanced_form('form_get_in_touch', [
                        'redirect' => '/thank-you',
                        'submit_text' => 'Get in Touch'
                    ]))
                @endif
            </div>
        </div>
    </div>
</div>
