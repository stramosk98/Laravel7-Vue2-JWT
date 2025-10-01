<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        Permission::create([
            'name' => 'view_orders',
            'type' => 1,
        ]);

        Permission::create([
            'name' => 'create_orders',
            'type' => 1,
        ]);

        Permission::create([
            'name' => 'update_orders',
            'type' => 1,
        ]);
        
        Permission::create([
            'name' => 'delete_orders',
            'type' => 1,
        ]);

        Permission::create([
            'name' => 'view_stock',
            'type' => 2,
        ]);

        Permission::create([
            'name' => 'view_clients',
            'type' => 3,
        ]);
    }
}
