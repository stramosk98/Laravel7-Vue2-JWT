<?php

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionsTableSeeder extends Seeder
{
    public function run()
    {
        RolePermission::firstOrCreate([
            'role_id' => 2,
            'permission_id' => 1,
        ]);

        RolePermission::firstOrCreate([
            'role_id' => 2,
            'permission_id' => 2,
        ]);

        RolePermission::firstOrCreate([
            'role_id' => 2,
            'permission_id' => 3,
        ]);
        
        RolePermission::firstOrCreate([
            'role_id' => 2,
            'permission_id' => 4,
        ]);
    }
}
