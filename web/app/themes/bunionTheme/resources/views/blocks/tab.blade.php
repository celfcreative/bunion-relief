<div class="tab">
    <div class="tab-title">
        <button class="btnTab" data-key="firstBtn">{{ $tabTitle }}</button>
        {{-- <InnerBlocks /> --}}
    </div>
    <div
        class="tab-body row align-items-center m-auto h-100 flex-column flex-md-row justify-content-center gap-3 gap-md-0">
        <div class="col-4">
            <img src="{{ $tabImage }}" alt="picture 1" class="tab-img w-100">
        </div>

        <div class="tab-description col-md-8 col-12">
            <h2>{{ $tabTitle }}</h2>
            <p>{{ $tabContent }}</p>
        </div>
    </div>
</div>
