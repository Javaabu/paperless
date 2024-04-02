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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('form_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('field_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('placeholder')->nullable();
            $table->string('slug');
            $table->string('language');
            $table->boolean('is_required')->index()->default(false);
            $table->string('type')->index(); // FormFieldTypes: Repeating Group, Text, Textarea, Select, Radio, Checkbox, Multiselect, Date
            $table->json('options')->nullable();
            $table->boolean('allow_custom_options')->default(false);
            $table->string('unit')->nullable();
            $table->bigInteger('min')->nullable();
            $table->bigInteger('max')->nullable();
            $table->unsignedInteger('order_column')->default(0);
            $table->text('additional_validation_rules')->nullable();
            $table->timestamps();

            $table->unique(['application_type_id', 'field_group_id', 'slug'], 'unique_form_field');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
