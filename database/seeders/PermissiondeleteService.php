<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissiondeleteService extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          Permission::firstOrCreate([
            'name' => 'borrar pago en servicio',
            'guard_name' => 'web',
        ]);
         Permission::firstOrCreate([
            'name' => 'borrar producto en servicio',
            'guard_name' => 'web',
        ]);
    }
}
