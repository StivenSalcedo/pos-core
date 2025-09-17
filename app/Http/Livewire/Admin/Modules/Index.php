<?php

namespace App\Http\Livewire\Admin\Modules;

use App\Models\Module;
use App\Models\Presentation;
use App\Models\Product;
use App\Services\ModuleService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class Index extends Component
{
    public $modules;
    public $functionalities;

    public function mount()
    {
        if (!isRoot()) abort(404);
        $this->getModules();
        $this->getFunctionalities();
    }

    public function render()
    {
        return view('livewire.admin.modules.index')->layoutData(['title' => 'Módulos']);
    }

    public function getModules()
    {
        $this->modules = Module::module()->get();
    }

    public function getFunctionalities()
    {
        $this->functionalities = Module::functionality()->get();
    }

    public function togglePermission(Module $module)
    {
        try {
            DB::beginTransaction();

            if ($module->is_enabled) {
                $this->removePermission($module);
            } else {
                $this->addModule($module);
            }

            $module->is_enabled = !$module->is_enabled;
            $module->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'ha ocurrido un error inesperado, vuelve al intentarlo.');
            return;
        }

        Artisan::call('cache:clear');

        $this->getModules();
        $this->getFunctionalities();
        $this->emitTo('admin.menu', 'render');
        $this->emit('success', 'Cambios realizados con éxito');
    }

    protected function addModule(Module $module)
    {
        if (!$module->is_functionality) {
            $permission = Permission::create(['name' => Str::lower($module->name)]);
            $permission->assignRole('Administrador');
        }

        ModuleService::refreshCache();
    }

    protected function removePermission(Module $module)
    {
        if ($module->is_functionality) {
            $this->disableInventoryProducts();
        } else {
            $permission = Permission::findByName(Str::lower($module->name));
            $permission->delete();
            Artisan::call('permission:cache-reset');
        }
        ModuleService::refreshCache();
    }

    public function enableAllModules()
    {
        $this->getModules();

        try {
            DB::beginTransaction();

            foreach ($this->modules as  $value) {
                if (!Permission::where('name', Str::lower($value->name))->exists()) {
                    $this->addModule($value);
                }
            }

            Module::query()->module()->update(['is_enabled' => 1]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'ha ocurrido un error inesperado, vuelve al intentarlo.');
            return;
        }

        $this->getModules();
        $this->emitTo('admin.menu', 'render');

        $this->emit('success', 'Todos los módulos se han habilitado con éxito');
    }

    protected function disableInventoryProducts()
    {
        Product::query()->update([
            'stock' => 0,
            'has_presentations' => '1',
            'quantity' => 0,
            'units' => 0,
            'has_inventory' => '1',
        ]);

        Presentation::query()->delete();
    }
}
