<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the pivot table
        Schema::create('tbl_vendor_vendor_types', function (Blueprint $table) {
            $table->integer('vendor_id');
            $table->integer('vendor_type_id');
            $table->primary(['vendor_id', 'vendor_type_id']);

            $table->foreign('vendor_id')->references('id')->on('tbl_vendors')->onDelete('cascade');
            $table->foreign('vendor_type_id')->references('id')->on('tbl_vendor_types')->onDelete('cascade');
        });

        // 2. Migrate existing data
        $vendors = DB::table('tbl_vendors')->whereNotNull('vendor_type_id')->get();
        foreach ($vendors as $vendor) {
            $typeExists = DB::table('tbl_vendor_types')->where('id', $vendor->vendor_type_id)->exists();
            if ($typeExists) {
                DB::table('tbl_vendor_vendor_types')->insertOrIgnore([
                    'vendor_id' => $vendor->id,
                    'vendor_type_id' => $vendor->vendor_type_id,
                ]);
            }
        }

        // 3. Drop the vendor_type_id column from tbl_vendors
        Schema::table('tbl_vendors', function (Blueprint $table) {
            $table->dropColumn('vendor_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add vendor_type_id column back to tbl_vendors
        Schema::table('tbl_vendors', function (Blueprint $table) {
            $table->integer('vendor_type_id')->nullable()->after('country_id');
        });

        // 2. Populate vendor_type_id with the first associated vendor_type_id from pivot
        $associations = DB::table('tbl_vendor_vendor_types')->get()->groupBy('vendor_id');
        foreach ($associations as $vendorId => $records) {
            if ($records->isNotEmpty()) {
                DB::table('tbl_vendors')
                    ->where('id', $vendorId)
                    ->update(['vendor_type_id' => $records->first()->vendor_type_id]);
            }
        }

        // 3. Drop pivot table
        Schema::dropIfExists('tbl_vendor_vendor_types');
    }
};
