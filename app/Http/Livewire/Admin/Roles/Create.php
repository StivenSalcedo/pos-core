<?php

namespace App\Http\Livewire\Admin\Roles;

use App\Http\Controllers\Log;
use App\Rules\Unique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class Create extends Component {

    protected $listeners=['openCreate'];

    public $open=false, $permissions;

    public $name, $permissionsSelected=[];

    public function mount(){
        $this->permissions = Permission::all()->pluck('name');
    }

    public function render() {
        return view('livewire.admin.roles.create');
    }

    public function openCreate(){
        $this->reset(['permissionsSelected', 'name', 'open']);
        $this->resetValidation();
        $this->open = true;
    }

    public function store(){

        $rules = [
            'name' => 'required|string|max:250|unique:roles',
            'permissionsSelected' => 'array'
        ];

        $this->validate($rules);

        $permissions = array_filter($this->permissionsSelected, fn($value) => $value !== false);
        $permissions = array_keys($permissions);

        try {
            DB::beginTransaction();

                $role = Role::create(['name' => $this->name]);
                $role->syncPermissions($permissions);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
        }

        $this->reset(['permissionsSelected', 'name', 'open']);

        $this->emit('success', 'Rol creado con éxito');
        $this->emitTo('admin.roles.index', 'render');


    }
}
