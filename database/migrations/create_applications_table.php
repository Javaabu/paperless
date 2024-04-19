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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
