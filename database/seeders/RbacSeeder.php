<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use Spatie\Permission\PermissionRegistrar;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Always clear Spatie's cache before changing permissions/roles
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create roles
        $roles = ['student', 'professor', 'secretary', 'finance', 'system_admin'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        // Create permissions
        $perms = [
            'view-dashboard', 'manage-students', 'manage-courses',
            'manage-finance', 'validate-grades', 'schedule-exams',
        ];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Attach permissions to roles
        Role::where('name', 'system_admin')->first()->givePermissionTo($perms);
        Role::where('name', 'secretary')->first()->givePermissionTo(['manage-students', 'schedule-exams', 'validate-grades']);
        Role::where('name', 'finance')->first()->givePermissionTo(['manage-finance']);
        Role::where('name', 'professor')->first()->givePermissionTo(['view-dashboard', 'validate-grades', 'schedule-exams']);
        Role::where('name', 'student')->first()->givePermissionTo(['view-dashboard']);
    }
}
