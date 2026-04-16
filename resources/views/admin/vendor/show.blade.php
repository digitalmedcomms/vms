@extends(backpack_view('layouts.top_left'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => backpack_url('dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.preview') => false,
  ];

  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
  <section class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small>{!! $crud->getSubheading() ?? trans('backpack::crud.preview').' '.$crud->entity_name !!}.</small>
      @if ($crud->hasAccess('list'))
        <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
      @endif
    </h2>
  </section>
@endsection

@section('content')
<div class="row">
    <div class="{{ $crud->getShowContentClass() }}">
        <div class="card no-padding no-border">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        @if($entry->logo)
                            <img src="{{ Storage::disk('public')->url($entry->logo) }}" alt="{{ $entry->name }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 200px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm mb-3" style="height: 200px; width: 100%;">
                                <i class="la la-image la-4x text-muted"></i>
                            </div>
                        @endif
                        <h3 class="mb-0">{{ $entry->name }}</h3>
                        <div class="text-muted mb-2">
                           <i class="la la-building"></i> {{ $entry->type->name ?? 'N/A' }}
                        </div>
                        <div class="badge badge-{{ $entry->status ? 'success' : 'secondary' }}">
                            {{ $entry->status ? 'Active' : 'Inactive' }}
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold text-uppercase small text-muted">Country</label>
                                <p class="mb-0 h6">{{ $entry->country->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold text-uppercase small text-muted">Website</label>
                                <p class="mb-0 h6">
                                    @if($entry->website_url)
                                        <a href="{{ $entry->website_url }}" target="_blank">{{ $entry->website_url }} <i class="la la-external-link-alt"></i></a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold text-uppercase small text-muted">Address</label>
                                <p class="mb-0 h6">{!!$entry->address ?? 'N/A' !!}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold text-uppercase small text-muted">Contact Information</label>
                                @if($entry->contacts && count($entry->contacts))
                                    <table class="table table-sm table-bordered mt-1">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Number</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entry->contacts as $contact)
                                                <tr>
                                                    <td>{{ $contact['name'] ?? 'N/A' }}</td>
                                                    <td>{{ $contact['number'] ?? 'N/A' }}</td>
                                                    <td>{{ $contact['email'] ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="mb-0 h6 text-muted">No contact persons listed.</p>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold text-uppercase small text-muted">TIN</label>
                                <p class="mb-0 h6">{{ $entry->tin ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0"><i class="la la-comments"></i> Vendor Comments</h4>
                    @php
                        $avgRating = $entry->comments()->avg('rating') ?: 0;
                    @endphp
                    <div class="h5 mb-0">
                        @include('vendor.backpack.ui.columns.star_rating', ['column' => ['value' => number_format($avgRating, 1)]])
                    </div>
                </div>

                <div class="table-responsive rounded shadow-sm">
                    @include('vendor.backpack.ui.columns.vendor_comments_list')
                </div>

                <hr class="my-4">

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card shadow-sm" style="border: 1px solid #e3e6f0; border-radius: 8px; overflow: hidden;">
                    <div class="card-header d-flex align-items-center py-2 px-3" style="background: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                        <i class="la la-edit mr-1 text-primary"></i>
                        <span class="font-weight-bold small">Add a Comment</span>
                    </div>
                    <div class="card-body px-3 py-3">
                        <form method="POST" action="{{ url(config('backpack.base.route_prefix') . '/vendor/' . $entry->id . '/comment') }}">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="small font-weight-bold mb-1">Rating <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center">
                                    <div class="star-rating d-flex flex-row-reverse" style="gap: 2px;">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                                class="d-none star-input"
                                                {{ old('rating') == $i ? 'checked' : '' }}>
                                            <label for="star{{ $i }}" class="star-label mb-0" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"
                                                style="cursor: pointer; font-size: 1.4rem; color: #d1d5db; transition: color 0.1s, transform 0.1s;">
                                                <i class="la la-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                    <span id="ratingText" class="text-muted ml-3" style="font-size: 0.78rem;">
                                        {{ old('rating') ? old('rating') . ' / 5 stars' : 'Click to rate' }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="small font-weight-bold mb-1" for="comment">Comment <span class="text-danger">*</span></label>
                                <textarea id="comment" name="comment" rows="3"
                                    class="form-control form-control-sm"
                                    placeholder="Write your comment here…"
                                    maxlength="1000">{{ old('comment') }}</textarea>
                                <div class="text-right">
                                    <small class="text-muted" style="font-size: 0.72rem;"><span id="charCount">{{ strlen(old('comment', '')) }}</span> / 1000</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="la la-paper-plane mr-1"></i> Submit Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after_styles')
<style>
    .star-label:hover {
        transform: scale(1.2);
    }
</style>
@endpush

@push('after_scripts')
<script>
(function () {
    var labels     = document.querySelectorAll('.star-label');
    var inputs     = document.querySelectorAll('.star-input');
    var ratingText = document.getElementById('ratingText');
    var charCount  = document.getElementById('charCount');
    var textarea   = document.getElementById('comment');

    var ratingLabels = { 1: '1 / 5 – Poor', 2: '2 / 5 – Fair', 3: '3 / 5 – Good', 4: '4 / 5 – Very Good', 5: '5 / 5 – Excellent' };

    function applyStarColour(selectedVal) {
        labels.forEach(function (label, idx) {
            var starVal = 5 - idx;
            label.querySelector('i').style.color = starVal <= selectedVal ? '#f6b93b' : '#d1d5db';
        });
    }

    // On change (click)
    inputs.forEach(function (input) {
        input.addEventListener('change', function () {
            var val = parseInt(this.value);
            applyStarColour(val);
            if (ratingText) ratingText.textContent = ratingLabels[val] || '';
        });

        // Restore colour from old('rating') on page load
        if (input.checked) {
            input.dispatchEvent(new Event('change'));
        }
    });

    // Hover preview
    labels.forEach(function (label, idx) {
        var hoverVal = 5 - idx;
        label.addEventListener('mouseenter', function () {
            applyStarColour(hoverVal);
        });
        label.addEventListener('mouseleave', function () {
            var checked = document.querySelector('.star-input:checked');
            applyStarColour(checked ? parseInt(checked.value) : 0);
        });
    });

    // Character counter
    if (textarea && charCount) {
        charCount.textContent = textarea.value.length;
        textarea.addEventListener('input', function () {
            charCount.textContent = this.value.length;
        });
    }
})();
</script>
@endpush
@endsection
