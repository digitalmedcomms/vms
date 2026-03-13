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
            $table->string('contact_person_2')->nullable()->after('contacts');
            $table->string('contact_number_2')->nullable()->after('contact_person_2');
            $table->string('email_address_2')->nullable()->after('contact_number_2');
            $table->dropColumn('contacts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_vendors', function (Blueprint $table) {
            $table->json('contacts')->nullable()->after('contact_person');
            $table->dropColumn(['contact_person_2', 'contact_number_2', 'email_address_2']);
        });
    }
};
