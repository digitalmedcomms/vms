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
            </div>
        </div>
    </div>
</div>
@endsection
