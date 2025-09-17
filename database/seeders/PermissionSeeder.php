<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder {

    public function run() {

        $permissions = [
            'dashboard',
            'usuarios',
            'clientes',
            'proveedores',
            'productos',
            'facturas',
            'cierre de caja',
            'financiaciones',
            'compras',
            'empleados',
            'nomina',
            'egresos',
            'configuraciones',
            'roles y permisos',
            'productos vendidos',
            'terminales',
            'rangos de numeración',
            'ver totales de venta'
        ];

        foreach ($permissions as $key => $value) {
            Permission::create(['name' => $value]);
        }
    }
}
