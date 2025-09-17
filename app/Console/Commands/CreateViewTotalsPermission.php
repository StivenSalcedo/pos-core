<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateViewTotalsPermission extends Command
{
    protected $signature = 'permission:create-view-totals {--force}';
    protected $description = 'Create view totals permission in database';
    protected $permission = 'ver totales de venta';

    public function handle()
    {
        if (!$this->option('force') && !$this->components->confirm('¿Está seguro de crear el permiso view_totals?')) {
            $this->components->error('Operación cancelada');
            return;
        }

        return DB::transaction(function () {
            if (!Permission::where('name', $this->permission)->exists()) {
                Permission::create([
                    'name' => $this->permission
                ]);
            }

            foreach (Role::all() as $role) {
                $role->givePermissionTo($this->permission);
            }

            $this->components->info('Permiso creado y asignado a todos los roles correctamente.');
        }, 1, function ($e) {
            $this->components->error('Error al crear el permiso: ' . $e->getMessage());
        });
    }
}
