<div class="{{ $block->classes }} my-4">

    @if ($files)
        @foreach ($files as $file)
            <div class="collapse-content">
                <a href="#" type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#downloadModal">Download</a>
            </div>
            <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-0">
                            <div>
                                <h2 class="modal-title px-3 text-primary fw-bold mb-3" id="downloadModalLabel">Research
                                    Paper/Case Study Name</h2>
                                <p class="px-3 mb-0">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit lacinia diam Suspendisse
                                    lacinia
                                    diam non augue varius sollicitudin.
                                </p>
                            </div>

                            @if (!$block->preview)
                                @php(advanced_form('form_resource_download', ['ajax' => true, 'values' => ['form_resource_download' => $file->ID]]))
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
