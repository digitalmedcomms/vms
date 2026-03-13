@extends(backpack_view('layouts.top_left'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.edit') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="{{ $crud->getEditContentClass() }}">
		{{-- Default box --}}

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route.'/'.$entry->getKey()) }}"
				@if ($crud->hasUploadFields('update', $entry->getKey()))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  {!! method_field('PUT') !!}

		  	@if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'edit' ])
		      @else
		      	@include('admin.vendor.form_content', [ 'fields' => $crud->fields(), 'action' => 'edit' ])
		      @endif

            @include('crud::inc.form_save_buttons')
		  </form>
	</div>
</div>
@endsection
