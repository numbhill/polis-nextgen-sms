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
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'department_id')) {
                $table->foreignUuid('department_id')->nullable()->after('id')
                    ->constrained('departments')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('courses', 'code')) {
                $table->string('code')->nullable()->unique()->after('department_id');
            }
            if (!Schema::hasColumn('courses', 'credits')) {
                $table->integer('credits')->nullable()->after('title');
            }
            if (!Schema::hasColumn('courses', 'description')) {
                $table->text('description')->nullable()->after('credits');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'department_id')) {
                $table->dropConstrainedForeignId('department_id');
            }
            foreach (['code', 'credits', 'description'] as $col) {
                if (Schema::hasColumn('courses', $col)) $table->dropColumn($col);
            }
        });
    }
};
