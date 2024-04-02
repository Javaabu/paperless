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
        Schema::create('form_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('form_field_id')->constrained()->cascadeOnDelete();
            $table->foreignId('field_group_id')->nullable()->constrained('field_groups')->cascadeOnDelete();
            $table->unsignedInteger('group_instance_number')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'form_field_id', 'field_group_id', 'group_instance_number'], 'unique_form_input');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_inputs');
    }
};
