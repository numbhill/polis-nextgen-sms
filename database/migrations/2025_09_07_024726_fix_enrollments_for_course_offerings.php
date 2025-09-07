<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add the new FK column (nullable for now so migration never fails)
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'course_offering_id')) {
                $table->foreignUuid('course_offering_id')
                    ->nullable()
                    ->after('student_id')
                    ->constrained('course_offerings')
                    ->cascadeOnDelete();   // FK to course_offerings.id
            }
        });

        // 2) Drop old unique index if it existed on (student_id, course_id)
        //    (name is the default Laravel pattern)
        DB::statement('DROP INDEX IF EXISTS enrollments_student_id_course_id_unique');

        // 3) Drop old legacy column if present
        if (Schema::hasColumn('enrollments', 'course_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropColumn('course_id');
            });
        }

        // 4) Add the correct unique constraint on (student_id, course_offering_id)
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'course_offering_id')) {
                $table->unique(
                    ['student_id', 'course_offering_id'],
                    'enrollments_student_id_course_offering_id_unique'
                );
            }
        });
    }

    public function down(): void
    {
        // Reverse: drop new unique, re-add course_id (nullable), drop course_offering_id
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'course_offering_id')) {
                $table->dropUnique('enrollments_student_id_course_offering_id_unique');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'course_id')) {
                $table->foreignUuid('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'course_offering_id')) {
                // FK name matches Laravel's default for the column
                $table->dropConstrainedForeignId('course_offering_id');
            }
        });

        // old unique could be restored if you need it:
        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS enrollments_student_id_course_id_unique ON enrollments (student_id, course_id)');
    }
};
