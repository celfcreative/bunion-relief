<div class="{{ $block->classes }}">
    <div class="stepper-single d-flex align-items-center gap-6" data-image="{{ $stepImage['url'] }}">
        <button type="button" class="stepper-indicator col-3 "></button>
        <div class="stepper-content w-100">
            <h3>{{ $stepTitle }}</h3>
            <p>{{ $stepContent }}</p>
        </div>
    </div>
</div>
