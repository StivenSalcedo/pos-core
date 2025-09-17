<?php

namespace App\Http\Livewire\Admin\Roles;

use App\Http\Controllers\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Edit extends Component {

    protected $listeners=['openEdit'];

    public $open=false, $permissions;

    public $role, $name, $permissionsSelected=[];

    public function mount(){
        $this->permissions = Permission::all()->pluck('name', 'id');
        $this->role = new Role();
    }

    public function render() {
        return view('livewire.admin.roles.edit');
    }

    public function openEdit(Role $role){

        if($role->name === 'administrador') return $this->emit('alert', 'No se puede editar el rol de admisintrador');

        $this->resetValidation();

        $this->open = true;

        $this->role = $role;
        $this->name = $role->name;

        $permissions = $role->permissions;

        foreach ($this->permissions as $key => $item) {

            $this->permissionsSelected[$item] = false;

            foreach ($permissions as $value) {

                if ($value->id === $key) {
                    $this->permissionsSelected[$item] = $item;
                }

            }

        }
    }

    public function update(){

        $rules = [
            'name' => 'required|max:250|unique:roles,name,' . $this->role->id,
            'permissionsSelected' => 'array'
        ];

        $this->validate($rules);

        if($this->role->name === 'administrador') return $this->emit('alert', 'No se puede editar el rol de administrador');

        $permissions = array_filter($this->permissionsSelected, fn($value) => $value !== false);
        $permissions = array_keys($permissions);

        try {
            DB::beginTransaction();

                Role::where('id', $this->role->id)->update(['name' => $this->name]);
                
                $this->role->syncPermissions($permissions);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
        }

        $this->reset(['permissionsSelected', 'name', 'open']);

        $this->emit('success', 'Rol actualizado con éxito');
        $this->emitTo('admin.roles.index', 'render');


    }
}
