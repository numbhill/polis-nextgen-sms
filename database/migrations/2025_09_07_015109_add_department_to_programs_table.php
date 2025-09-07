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
        if (!Schema::hasColumn('programs', 'department_id')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->foreignUuid('department_id')->nullable()->after('id')->constrained('departments')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('programs', 'department_id')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->dropConstrainedForeignId('department_id');
            });
        }
    }
};
