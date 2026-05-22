@php
    $countries = \App\Models\Country::orderBy('name')->get();
    $vendorTypes = \App\Models\VendorType::orderBy('name')->get();
    
    $selectedCountry = request('country_id');
    $selectedType = request('type_id');
    $selectedStatus = request('status');
@endphp

<div class="card mb-3 border-0 shadow-sm bg-white">
    <div class="card-body p-3">
        <form method="GET" action="{{ url($crud->route) }}" id="vendorFilterForm" class="row align-items-end">
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="form-label font-weight-bold text-uppercase small text-muted mb-1">Vendor Type</label>
                <select name="type_id" class="form-control form-control-sm select-filter" onchange="this.form.submit()">
                    <option value="">-- All Vendor Types --</option>
                    @foreach($vendorTypes as $type)
                        <option value="{{ $type->id }}" {{ $selectedType == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="form-label font-weight-bold text-uppercase small text-muted mb-1">Country</label>
                <select name="country_id" class="form-control form-control-sm select-filter" onchange="this.form.submit()">
                    <option value="">-- All Countries --</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ $selectedCountry == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="form-label font-weight-bold text-uppercase small text-muted mb-1">Status</label>
                <select name="status" class="form-control form-control-sm select-filter" onchange="this.form.submit()">
                    <option value="">-- All Statuses --</option>
                    <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 text-md-right text-left mt-2 mt-md-0">
                @if($selectedType || $selectedCountry || isset($selectedStatus))
                    <a href="{{ url($crud->route) }}" class="btn btn-sm btn-outline-secondary w-100 w-md-auto">
                        <i class="la la-filter-slash"></i> Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
