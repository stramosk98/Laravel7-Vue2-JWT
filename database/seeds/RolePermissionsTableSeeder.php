<?php

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionsTableSeeder extends Seeder
{
    public function run()
    {
        RolePermission::create([
            'role_id' => 2,
            'permission_id' => 1,
        ]);

        RolePermission::create([
            'role_id' => 2,
            'permission_id' => 2,
        ]);

        RolePermission::create([
            'role_id' => 2,
            'permission_id' => 3,
        ]);
        
        RolePermission::create([
            'role_id' => 2,
            'permission_id' => 4,
        ]);
    }
}
