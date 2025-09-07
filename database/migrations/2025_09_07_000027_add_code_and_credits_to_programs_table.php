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
        Schema::table('programs', function (Blueprint $table) {
            Schema::table('programs', function (Blueprint $table) {
                if (!Schema::hasColumn('programs', 'code')) {
                    $table->string('code')->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('programs', 'credits_required')) {
                    $table->integer('credits_required')->nullable()->after('name');
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'credits_required')) {
                $table->dropColumn('credits_required');
            }
            if (Schema::hasColumn('programs', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
