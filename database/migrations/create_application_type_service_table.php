<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('application_type_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->boolean('is_applied_automatically')->default(true);
            $table->timestamps();

            $table->unique(['application_type_id', 'service_id'], 'unique_application_type_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_type_service');
    }
};
