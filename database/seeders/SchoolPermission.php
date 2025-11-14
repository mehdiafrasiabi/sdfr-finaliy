<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SchoolPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage school staff',
            'assign school staff to student',
            'view own students',
            'edit own students',
            'view school reports',
            'upload weekly program',
        ];


        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name'=>$p, 'guard_name'=>'school']);
        }


        $manager = Role::firstOrCreate(['name'=>'school_manager','guard_name'=>'school']);
        $manager->givePermissionTo(['manage school staff','assign school staff to student','view own students','edit own students','view school reports','upload weekly program']);


        $supporter = Role::firstOrCreate(['name'=>'school_supporter','guard_name'=>'school']);
        $supporter->givePermissionTo(['view own students','edit own students','view school reports']);


        $advisor = Role::firstOrCreate(['name'=>'school_advisor','guard_name'=>'school']);
        $advisor->givePermissionTo(['view own students','view school reports','upload weekly program']);

    }
}
