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

        return view('admin.dashboard', [
            'title' => trans('backpack::base.dashboard'),
            'breadcrumbs' => [
                trans('backpack::crud.admin') => backpack_url('dashboard'),
                trans('backpack::base.dashboard') => false,
            ],
            'highestRatedVendor' => $highestRatedVendor,
        ]);
    }
}
