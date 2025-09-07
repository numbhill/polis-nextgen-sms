<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Add in the right spot, guarded by existence checks
            if (!Schema::hasColumn('enrollments', 'status')) {
                // 'enrolled|dropped|completed' (store as string; enforce via app rules)
                $table->string('status')->default('enrolled')->after('course_offering_id');
            }
            if (!Schema::hasColumn('enrollments', 'enrolled_at')) {
                $table->timestamp('enrolled_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'enrolled_at')) {
                $table->dropColumn('enrolled_at');
            }
            if (Schema::hasColumn('enrollments', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
