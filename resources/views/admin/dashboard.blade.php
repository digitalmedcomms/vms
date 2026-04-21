@extends(backpack_view('layouts.top_left'))

@section('header')
    <section class="container-fluid">
        <h1>{{ trans('backpack::base.dashboard') }}</h1>
    </section>
@endsection

@section('content')
    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-lg bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.75rem; opacity: 0.8;">Total Vendors</h6>
                            <h2 class="mb-0 font-weight-bold">{{ $totalVendors }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="la la-users la-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-lg bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.75rem; opacity: 0.8;">Total Ratings</h6>
                            <h2 class="mb-0 font-weight-bold">{{ $totalComments }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="la la-star la-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-lg bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.75rem; opacity: 0.8;">Avg. Rating</h6>
                            <h2 class="mb-0 font-weight-bold">{{ $averageRating }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="la la-chart-bar la-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Highest Rated Vendor Detailed --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg overflow-hidden h-100">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0"><i class="la la-award"></i> Highest Rated Vendor</h5>
                </div>
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    @if($highestRatedVendor)
                        @if($highestRatedVendor->logo)
                            <img src="{{ Storage::disk('public')->url($highestRatedVendor->logo) }}" alt="{{ $highestRatedVendor->name }}" class="img-fluid rounded shadow-sm mb-3 mx-auto" style="max-height: 100px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm mb-3 mx-auto" style="height: 100px; width: 100px;">
                                <i class="la la-image la-3x text-muted"></i>
                            </div>
                        @endif
                        <h4 class="mb-1">{{ $highestRatedVendor->name }}</h4>
                        <div class="h5 mb-3 text-warning">
                            @include('vendor.backpack.ui.columns.star_rating', ['column' => ['value' => number_format($highestRatedVendor->average_rating, 1)]])
                        </div>
                        <p class="text-muted small mb-3">{{ $highestRatedVendor->type->name ?? '' }} | {{ $highestRatedVendor->country->name ?? '' }}</p>
                        <a href="{{ backpack_url('vendor/' . $highestRatedVendor->id . '/show') }}" class="btn btn-primary btn-sm rounded-pill px-4 mt-auto">
                            View Profile
                        </a>
                    @else
                        <p class="text-muted mb-0">No ratings yet.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Most Commented Vendor Detailed --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg overflow-hidden h-100">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0"><i class="la la-comments"></i> Most Commented Vendor</h5>
                </div>
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    @if($mostCommentedVendor)
                        @if($mostCommentedVendor->logo)
                            <img src="{{ Storage::disk('public')->url($mostCommentedVendor->logo) }}" alt="{{ $mostCommentedVendor->name }}" class="img-fluid rounded shadow-sm mb-3 mx-auto" style="max-height: 100px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm mb-3 mx-auto" style="height: 100px; width: 100px;">
                                <i class="la la-image la-3x text-muted"></i>
                            </div>
                        @endif
                        <h4 class="mb-1">{{ $mostCommentedVendor->name }}</h4>
                        <div class="h5 mb-3 text-primary">
                            <span class="badge badge-primary badge-pill px-3 py-2">
                                <i class="la la-comment mr-1"></i> {{ $mostCommentedVendor->comment_count }} Comments
                            </span>
                        </div>
                        <p class="text-muted small mb-3">{{ $mostCommentedVendor->type->name ?? '' }} | {{ $mostCommentedVendor->country->name ?? '' }}</p>
                        <a href="{{ backpack_url('vendor/' . $mostCommentedVendor->id . '/show') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-auto">
                            View Profile
                        </a>
                    @else
                        <p class="text-muted mb-0">No comments yet.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recently Added Vendor Detailed --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg overflow-hidden h-100">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0"><i class="la la-plus-circle"></i> Recently Added Vendor</h5>
                </div>
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    @if($recentlyAddedVendor)
                        @if($recentlyAddedVendor->logo)
                            <img src="{{ Storage::disk('public')->url($recentlyAddedVendor->logo) }}" alt="{{ $recentlyAddedVendor->name }}" class="img-fluid rounded shadow-sm mb-3 mx-auto" style="max-height: 100px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm mb-3 mx-auto" style="height: 100px; width: 100px;">
                                <i class="la la-image la-3x text-muted"></i>
                            </div>
                        @endif
                        <h4 class="mb-1">{{ $recentlyAddedVendor->name }}</h4>
                        <div class="h5 mb-3 text-info">
                            <span class="badge badge-info badge-pill px-3 py-2">
                                <i class="la la-calendar mr-1"></i> Added {{ \Carbon\Carbon::parse($recentlyAddedVendor->created_when)->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-muted small mb-3">{{ $recentlyAddedVendor->type->name ?? '' }} | {{ $recentlyAddedVendor->country->name ?? '' }}</p>
                        <a href="{{ backpack_url('vendor/' . $recentlyAddedVendor->id . '/show') }}" class="btn btn-outline-info btn-sm rounded-pill px-4 mt-auto">
                            View Profile
                        </a>
                    @else
                        <p class="text-muted mb-0">No vendors added yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-bottom-0 p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">Recent Ratings & Comments</h5>
                    <a href="{{ backpack_url('vendor-comment') }}" class="btn btn-sm btn-link">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Vendor</th>
                                    <th class="border-0">User</th>
                                    <th class="border-0">Rating</th>
                                    <th class="border-0">Comment</th>
                                    <th class="border-0">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentComments as $comment)
                                    <tr>
                                        <td>
                                            <a href="{{ backpack_url('vendor/' . $comment->vendor_id . '/show') }}" class="font-weight-bold">
                                                {{ $comment->vendor->name ?? 'Unknown' }}
                                            </a>
                                        </td>
                                        <td>{{ $comment->user->name ?? 'System' }}</td>
                                        <td>
                                            @include('vendor.backpack.ui.columns.star_rating', ['column' => ['value' => $comment->rating]])
                                        </td>
                                        <td>
                                            <span class="text-muted small" title="{{ $comment->comment }}">
                                                {{ Str::limit($comment->comment, 50) }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ \Carbon\Carbon::parse($comment->insert_date)->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4 text-muted">No recent comments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
