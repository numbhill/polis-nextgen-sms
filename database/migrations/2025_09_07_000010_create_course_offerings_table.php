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
        Schema::create('course_offerings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignUuid('professor_id')->constrained('professors')->cascadeOnDelete();
            $table->string('term');      // e.g., 2025-FALL
            $table->string('section');   // e.g., A
            $table->jsonb('schedule_meta')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            // $table->unique(['course_id','term','section']); // optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_offerings');
    }
};
