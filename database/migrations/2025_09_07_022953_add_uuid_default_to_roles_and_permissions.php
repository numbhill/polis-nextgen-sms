<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Ensure the extension exists (safe if already enabled)
            DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

            // Make Postgres auto-generate UUIDs for these PKs
            DB::statement('ALTER TABLE roles ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
            DB::statement('ALTER TABLE permissions ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE roles ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE permissions ALTER COLUMN id DROP DEFAULT;');
        }
    }
};
