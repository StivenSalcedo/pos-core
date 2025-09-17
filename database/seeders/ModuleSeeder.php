<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            [
                'name' => 'dashboard',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'usuarios',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'clientes',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'proveedores',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'productos',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'facturas',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'cierre de caja',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'financiaciones',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'compras',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'empleados',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'nomina',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'egresos',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'configuraciones',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'roles y permisos',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'productos vendidos',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'terminales',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'rangos de numeración',
                'is_enabled' => 1,
                'is_functionality' => 0
            ],
            [
                'name' => 'ventas rapidas',
                'is_enabled' => 0,
                'is_functionality' => 0
            ],
            [
                'name' => 'reporte de ventas diarias',
                'is_enabled' => 0,
                'is_functionality' => 0
            ],
            [
                'name' => 'impuestos',
                'is_enabled' => 0,
                'is_functionality' => 0
            ],
            [
                'name' => 'inventario',
                'is_enabled' => 0,
                'is_functionality' => 1
            ]
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
