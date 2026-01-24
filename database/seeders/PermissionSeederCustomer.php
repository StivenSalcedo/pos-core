<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionSeederCustomer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate([
            'name' => 'editar informacion del cliente',
            'guard_name' => 'web',
        ]);
         Permission::firstOrCreate([
            'name' => 'cambiar cliente en servicio',
            'guard_name' => 'web',
        ]);
    }
}
