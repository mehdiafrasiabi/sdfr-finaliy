<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            'phone-acquisition.consult',
            'acquisition.dashboard',
            'acquisition.contacts',
            'acquisition.student',
            'acquisition.monitor',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'admin'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        DB::table('roles')->updateOrInsert(
            ['name' => 'مشاور جذب تلفنی', 'guard_name' => 'admin'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $roleId = DB::table('roles')
            ->where('name', 'مشاور جذب تلفنی')
            ->where('guard_name', 'admin')
            ->value('id');

        if (! $roleId) {
            return;
        }

        DB::table('permissions')
            ->where('guard_name', 'admin')
            ->whereIn('name', $permissions)
            ->pluck('id')
            ->each(function ($permissionId) use ($roleId) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            });
    }

    public function down(): void
    {
        $roleId = DB::table('roles')
            ->where('name', 'مشاور جذب تلفنی')
            ->where('guard_name', 'admin')
            ->value('id');

        if (! $roleId) {
            return;
        }

        $permissionIds = DB::table('permissions')
            ->where('guard_name', 'admin')
            ->whereIn('name', [
                'acquisition.dashboard',
                'acquisition.contacts',
                'acquisition.student',
                'acquisition.monitor',
            ])
            ->pluck('id');

        DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->whereIn('permission_id', $permissionIds)
            ->delete();
    }
};
