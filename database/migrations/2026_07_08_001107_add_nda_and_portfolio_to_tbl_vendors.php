<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_vendors', function (Blueprint $table) {
            $table->boolean('with_signed_mims_nda')->default(false)->after('website_url');
            $table->string('portfolio_link', 500)->nullable()->after('with_signed_mims_nda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_vendors', function (Blueprint $table) {
            $table->dropColumn(['with_signed_mims_nda', 'portfolio_link']);
        });
    }
};
