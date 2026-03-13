@extends(backpack_view('layouts.top_left'))

@section('header')
    <section class="container-fluid">
        <h1>{{ trans('backpack::base.dashboard') }}</h1>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-primary text-white p-3">
                    <h5 class="mb-0"><i class="la la-star"></i> Highest Rated Vendor</h5>
                </div>
                <div class="card-body p-4 text-center">
                    @if($highestRatedVendor)
                        @if($highestRatedVendor->logo)
                            <img src="{{ Storage::disk('public')->url($highestRatedVendor->logo) }}" alt="{{ $highestRatedVendor->name }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 120px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm mb-3 mx-auto" style="height: 120px; width: 120px;">
                                <i class="la la-image la-3x text-muted"></i>
                            </div>
                        @endif
                        <h4 class="mb-1">{{ $highestRatedVendor->name }}</h4>
                        <div class="h5 mb-3">
                            @include('vendor.backpack.ui.columns.star_rating', ['column' => ['value' => number_format($highestRatedVendor->average_rating, 1)]])
                        </div>
                        <a href="{{ backpack_url('vendor/' . $highestRatedVendor->id . '/show') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                            View Details
                        </a>
                    @else
                        <p class="text-muted mb-0">No ratings yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
