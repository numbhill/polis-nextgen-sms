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
        // Enable the uuid-ossp extension for UUID generation
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Usually we do NOT drop the extension in down(), to avoid breaking existing UUID columns
        // DB::statement('DROP EXTENSION IF EXISTS "uuid-ossp";');
    }
};
