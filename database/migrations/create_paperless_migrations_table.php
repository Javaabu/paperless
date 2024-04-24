<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaperlessMigrationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('entity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('fee', 14, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('media', function (Blueprint $table) {
            $table->foreignId('document_type_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::create('application_types', function (Blueprint $table) {
            $table->id();
            // file: add svg icon for each application type
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('eta_duration');
            $table->string('application_category')->index();
            $table->boolean('allow_additional_documents')->default(false);
            $table->timestamps();
        });

        Schema::create('entity_type_application_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_type_id')->constrained('entity_types')->cascadeOnDelete();
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['entity_type_id', 'application_type_id'], 'unique_entity_type_application_type');
        });

        Schema::create('application_type_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->boolean('is_applied_automatically')->default(true);
            $table->timestamps();

            $table->unique(['application_type_id', 'service_id'], 'unique_application_type_service');
        });

        Schema::create('document_type_application_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types')->cascadeOnDelete();
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->unique(['document_type_id', 'application_type_id'], 'unique_document_type_application_type');
        });

        Schema::create('form_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order_column');
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();
            $table->boolean('is_admin_section')->default(false);
            $table->timestamps();
        });

        Schema::create('field_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order_column');
            $table->foreignId('form_section_id')->constrained('form_sections')->cascadeOnDelete();
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();
            $table->timestamps();
        });

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
            $table->string('builder')->index(); // FormFieldTypes: Repeating Group, Text, Textarea, Select, Radio, Checkbox, Multiselect, Date
            $table->unsignedInteger('order_column')->default(0);
            $table->timestamps();

            $table->unique(['application_type_id', 'field_group_id', 'slug'], 'unique_form_field');
        });

        Schema::create('applications', function (Blueprint $table) {
            $public_user_table = config('paperless.public_user_table');

            $table->id();
            $table->foreignId('public_user_id')->nullable()->constrained($public_user_table)->nullOnDelete();
            $table->morphs('applicant'); // individual and entity
            $table->foreignId('application_type_id')->constrained('application_types')->cascadeOnDelete();

            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('verified_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->dateTime('eta_at')->nullable();
            $table->string('status')->index();

            $table->nullableMorphs('related');
            $table->nullableMorphs('generated');
            $table->softDeletes();
            $table->timestamps();
        });

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

    public function down(): void
    {
        Schema::dropIfExists('entity_types');
        Schema::dropIfExists('services');
        Schema::dropIfExists('document_types');

        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['document_type_id']);
            $table->dropColumn('document_type_id');
        });

        Schema::dropIfExists('application_types');
        Schema::dropIfExists('entity_type_application_type');
        Schema::dropIfExists('application_type_service');
        Schema::dropIfExists('document_type_application_type');
        Schema::dropIfExists('form_sections');
        Schema::dropIfExists('field_groups');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('form_inputs');
    }
}
