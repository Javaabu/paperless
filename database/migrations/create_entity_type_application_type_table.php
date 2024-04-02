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
        Schema::create('entity_type_application_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_type_id')->constrained('entity_types')->cascadeOnDelete();
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['entity_type_id', 'application_type_id'], 'unique_entity_type_application_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_type_application_type');
    }
};
