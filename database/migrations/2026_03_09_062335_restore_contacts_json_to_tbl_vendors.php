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
            if (!Schema::hasColumn('tbl_vendors', 'contacts')) {
                $table->json('contacts')->nullable()->after('contact_person');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_vendors', function (Blueprint $table) {
            $table->dropColumn('contacts');
        });
    }
};
