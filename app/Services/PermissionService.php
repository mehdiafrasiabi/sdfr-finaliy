<?php


namespace App\Services;


use Database\Seeders\AcademicAdvisorPermissionSeeder;

use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;


class PermissionService

{

    /**
     * گرفتن تمام گروه‌های دسترسی با نام فارسی
     */

    public static function getPermissionGroups(): array

    {

        return AcademicAdvisorPermissionSeeder::getPermissionGroups();

    }


    /**
     * گرفتن دسترسی‌ها به صورت گروه‌بندی شده برای نمایش در UI
     */

    public static function getGroupedPermissions(): array

    {

        $groups = self::getPermissionGroups();

        $result = [];


        foreach ($groups as $groupName => $permissions) {

            $groupPermissions = [];

            foreach ($permissions as $persianName => $permissionKey) {

                $permission = Permission::where('name', $permissionKey)
                    ->where('guard_name', 'admin')
                    ->first();


                if ($permission) {

                    $groupPermissions[] = [

                        'id' => $permission->id,

                        'key' => $permissionKey,

                        'persian_name' => $persianName,

                    ];

                }

            }


            if (!empty($groupPermissions)) {

                $result[] = [

                    'group_name' => $groupName,

                    'permissions' => $groupPermissions,

                ];

            }

        }


        return $result;

    }


    /**
     * گرفتن نام فارسی دسترسی
     */

    public static function getPersianName(string $permissionKey): string

    {

        return AcademicAdvisorPermissionSeeder::getPermissionPersianName($permissionKey);

    }


    /**
     * گرفتن نام گروه برای یک دسترسی
     */

    public static function getGroupName(string $permissionKey): string

    {

        return AcademicAdvisorPermissionSeeder::getGroupNameByPermission($permissionKey);

    }


    /**
     * گرفتن تمام نقش‌ها با نام فارسی
     */

    public static function getRoles(): array

    {

        $persianRoles = [

            'super admin'         => 'مدیر کل',

            'educational-manager' => 'مدیر آموزشی',

            'مشاور تحصیلی'        => 'مشاور تحصیلی',

            'site acquisition'    => 'پشتیبان جذب',

        ];


        $roles = Role::where('guard_name', 'admin')->get();

        $result = [];


        foreach ($roles as $role) {

            $result[] = [

                'id' => $role->id,

                'name' => $role->name,

                'persian_name' => $persianRoles[$role->name] ?? $role->name,

            ];

        }


        return $result;

    }


    /**
     * چک کردن دسترسی کاربر
     */

    public static function checkPermission($admin, string $permissionKey): bool

    {

        if (!$admin) {

            return false;

        }


        // اگر super admin باشه همه دسترسی‌ها رو داره

        if ($admin->hasRole('super admin')) {

            return true;

        }


        return $admin->hasPermissionTo($permissionKey);

    }


    /**
     * گرفتن دسترسی‌های یک ادمین به صورت گروه‌بندی شده
     */

    public static function getAdminPermissionsGrouped($admin): array

    {

        if (!$admin) {

            return [];

        }


        $allGroups = self::getPermissionGroups();

        $adminPermissions = $admin->getAllPermissions()->pluck('name')->toArray();

        $result = [];


        foreach ($allGroups as $groupName => $permissions) {

            $groupData = [

                'group_name' => $groupName,

                'permissions' => [],

                'has_any' => false,

            ];


            foreach ($permissions as $persianName => $permissionKey) {

                $hasPermission = in_array($permissionKey, $adminPermissions) || $admin->hasRole('super admin');

                $groupData['permissions'][] = [

                    'key' => $permissionKey,

                    'persian_name' => $persianName,

                    'has_permission' => $hasPermission,

                ];


                if ($hasPermission) {

                    $groupData['has_any'] = true;

                }

            }


            $result[] = $groupData;

        }


        return $result;

    }


    /**
     * همگام‌سازی دسترسی‌های یک ادمین
     */

    public static function syncAdminPermissions($admin, array $permissionIds): void

    {

        $permissions = Permission::whereIn('id', $permissionIds)
            ->where('guard_name', 'admin')
            ->get();


        $admin->syncPermissions($permissions);

    }


    /**
     * همگام‌سازی نقش‌های یک ادمین
     */

    public static function syncAdminRoles($admin, array $roleIds): void

    {

        $roles = Role::whereIn('id', $roleIds)
            ->where('guard_name', 'admin')
            ->get();


        $admin->syncRoles($roles);

    }

}
