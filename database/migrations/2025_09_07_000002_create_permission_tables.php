<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $teams        = config('permission.teams');
        $tableNames   = config('permission.table_names');
        $columnNames  = config('permission.column_names');

        $pivotRole       = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $morphKey        = $columnNames['model_morph_key'] ?? 'model_id';

        throw_if(empty($tableNames), new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.'));
        throw_if($teams && empty($columnNames['team_foreign_key'] ?? null), new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.'));

        // ===== permissions =====
        Schema::create($tableNames['permissions'], static function (Blueprint $table) {
            $table->uuid('id')->primary();    // UUID PK
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        // ===== roles =====
        Schema::create($tableNames['roles'], static function (Blueprint $table) use ($teams, $columnNames) {
            $table->uuid('id')->primary();    // UUID PK

            // Optional team/tenant column (kept as UUID for consistency)
            if ($teams || config('permission.testing')) {
                $table->uuid($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }

            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        // ===== model_has_permissions (pivot: permissions <-> models) =====
        Schema::create($tableNames['model_has_permissions'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams, $morphKey) {
            $table->foreignUuid($pivotPermission)
                ->constrained($tableNames['permissions'])
                ->cascadeOnDelete();        // FK to permissions.id (UUID)

            $table->string('model_type');
            $table->uuid($morphKey);          // UUID morph key (matches users.id)
            $table->index([$morphKey, 'model_type'], 'model_has_permissions_model_id_model_type_index');

            if ($teams) {
                $table->uuid($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $table->primary([
                    $columnNames['team_foreign_key'], $pivotPermission, $morphKey, 'model_type'
                ], 'model_has_permissions_permission_model_type_primary');
            } else {
                $table->primary([
                    $pivotPermission, $morphKey, 'model_type'
                ], 'model_has_permissions_permission_model_type_primary');
            }
        });

        // ===== model_has_roles (pivot: roles <-> models) =====
        Schema::create($tableNames['model_has_roles'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams, $morphKey) {
            $table->foreignUuid($pivotRole)
                ->constrained($tableNames['roles'])
                ->cascadeOnDelete();        // FK to roles.id (UUID)

            $table->string('model_type');
            $table->uuid($morphKey);          // UUID morph key
            $table->index([$morphKey, 'model_type'], 'model_has_roles_model_id_model_type_index');

            if ($teams) {
                $table->uuid($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary([
                    $columnNames['team_foreign_key'], $pivotRole, $morphKey, 'model_type'
                ], 'model_has_roles_role_model_type_primary');
            } else {
                $table->primary([
                    $pivotRole, $morphKey, 'model_type'
                ], 'model_has_roles_role_model_type_primary');
            }
        });

        // ===== role_has_permissions (pivot: roles <-> permissions) =====
        Schema::create($tableNames['role_has_permissions'], static function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) {
            $table->foreignUuid($pivotPermission)
                ->constrained($tableNames['permissions'])
                ->cascadeOnDelete();        // FK to permissions.id (UUID)

            $table->foreignUuid($pivotRole)
                ->constrained($tableNames['roles'])
                ->cascadeOnDelete();        // FK to roles.id (UUID)

            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        // Clear Spatie permission cache
        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
};
