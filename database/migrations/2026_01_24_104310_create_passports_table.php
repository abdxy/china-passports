<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->string('full_name');
            $table->string('passport_id'); // Not unique as per user request
            $table->string('passport_image_url')->nullable();
            $table->string('personal_image_url')->nullable();
            $table->string('old_visa_url')->nullable();
            $table->boolean('have_china_previous_visa')->default(false);
            $table->enum('status', ['applied', 'rejected', 'waiting_reciveing_passport', 'sent_to_jordan', 'in_embassy', 'sent_to_iraq'])->default('applied');
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('payment_status', ['not_paid', 'paid'])->default('not_paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
