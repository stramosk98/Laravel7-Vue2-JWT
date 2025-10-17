<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        Permission::firstOrCreate(
            ['name' => 'view_orders'],
            ['type' => 1]
        );

        Permission::firstOrCreate(
            ['name' => 'create_orders'],
            ['type' => 1]
        );

        Permission::firstOrCreate(
            ['name' => 'update_orders'],
            ['type' => 1]
        );
        
        Permission::firstOrCreate(
            ['name' => 'delete_orders'],
            ['type' => 1]
        );

        Permission::firstOrCreate(
            ['name' => 'view_stock'],
            ['type' => 2]
        );

        Permission::firstOrCreate(
            ['name' => 'view_clients'],
            ['type' => 3]
        );
    }
}
