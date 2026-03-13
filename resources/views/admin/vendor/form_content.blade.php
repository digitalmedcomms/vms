<div class="row">
    {{-- General Information Section --}}
    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="la la-info-circle"></i> General Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                         @include($crud->getFirstFieldView($fields['logo']['type'], $fields['logo']['view_namespace'] ?? false), ['field' => $fields['logo']])
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                @include($crud->getFirstFieldView($fields['name']['type'], $fields['name']['view_namespace'] ?? false), ['field' => $fields['name']])
                            </div>
                            <div class="col-md-6 mb-3">
                                @include($crud->getFirstFieldView($fields['vendor_type_id']['type'], $fields['vendor_type_id']['view_namespace'] ?? false), ['field' => $fields['vendor_type_id']])
                            </div>
                            <div class="col-md-6 mb-3">
                                @include($crud->getFirstFieldView($fields['country_id']['type'], $fields['country_id']['view_namespace'] ?? false), ['field' => $fields['country_id']])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details & Identification Section --}}
    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="la la-id-card"></i> Details & Identification</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        @include($crud->getFirstFieldView($fields['address']['type'], $fields['address']['view_namespace'] ?? false), ['field' => $fields['address']])
                    </div>
                    <div class="col-md-6 mb-3">
                        @include($crud->getFirstFieldView($fields['tin']['type'], $fields['tin']['view_namespace'] ?? false), ['field' => $fields['tin']])
                    </div>
                    <div class="col-md-6 mb-3">
                        @include($crud->getFirstFieldView($fields['status']['type'], $fields['status']['view_namespace'] ?? false), ['field' => $fields['status']])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Information Section (Dynamic) --}}
    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-header bg-info text-white py-3">
                <h5 class="mb-0"><i class="la la-users"></i> Contact Information</h5>
            </div>
            <div class="card-body p-4">
                @include($crud->getFirstFieldView($fields['contacts']['type'], $fields['contacts']['view_namespace'] ?? false), ['field' => $fields['contacts']])
            </div>
        </div>
    </div>

    {{-- Online Presence Section --}}
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-header bg-secondary text-white py-3">
                <h5 class="mb-0"><i class="la la-globe"></i> Online Presence</h5>
            </div>
            <div class="card-body p-4">
                @include($crud->getFirstFieldView($fields['website_url']['type'], $fields['website_url']['view_namespace'] ?? false), ['field' => $fields['website_url']])
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-2px); }
    .card-header { font-weight: 600; letter-spacing: 0.5px; }
    .form-group label { font-weight: 500; color: #495057; }
</style>
