<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $highestRatedVendor = Vendor::select('tbl_vendors.*')
            ->leftJoin('tbl_vendor_comments', 'tbl_vendors.id', '=', 'tbl_vendor_comments.vendor_id')
            ->groupBy('tbl_vendors.id')
            ->selectRaw('AVG(tbl_vendor_comments.rating) as average_rating')
            ->orderByDesc('average_rating')
            ->first();

        $totalVendors = Vendor::count();
        $totalComments = \App\Models\VendorComment::count();
        $averageRating = \App\Models\VendorComment::avg('rating') ?: 0;
        
        $mostCommentedVendor = Vendor::select('tbl_vendors.*', DB::raw('count(tbl_vendor_comments.id) as comment_count'))
            ->leftJoin('tbl_vendor_comments', 'tbl_vendors.id', '=', 'tbl_vendor_comments.vendor_id')
            ->groupBy('tbl_vendors.id')
            ->orderByDesc('comment_count')
            ->first();

        $recentlyAddedVendor = Vendor::orderByDesc('created_when')
            ->first();

        $recentComments = \App\Models\VendorComment::with(['vendor', 'user'])
            ->orderByDesc('insert_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'title' => trans('backpack::base.dashboard'),
            'breadcrumbs' => [
                trans('backpack::crud.admin') => backpack_url('dashboard'),
                trans('backpack::base.dashboard') => false,
            ],
            'highestRatedVendor' => $highestRatedVendor,
            'mostCommentedVendor' => $mostCommentedVendor,
            'recentlyAddedVendor' => $recentlyAddedVendor,
            'totalVendors' => $totalVendors,
            'totalComments' => $totalComments,
            'averageRating' => number_format($averageRating, 1),
            'recentComments' => $recentComments,
        ]);
    }
}
