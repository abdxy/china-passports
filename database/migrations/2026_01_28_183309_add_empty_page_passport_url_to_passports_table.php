<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->string('empty_page_passport_url')->nullable()->after('old_visa_url');
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropColumn('empty_page_passport_url');
        });
    }
};
