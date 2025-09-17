<?php

namespace App\Http\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class Index extends Component {

    protected $listeners = ['render'];

    public function render() {

        $roles = Role::withCount('users')->withCount('permissions')->get();

        return view('livewire.admin.roles.index', compact('roles'))->layoutData(['title' => 'Roles y permisos']);
    }

    public function destroy(Role $role){

        if($role->name === 'Administrador') return $this->emit('alert', 'No se puede eliminar el rol de admisintrador');

        if ($role->users->count()) return $this->emit('alert', 'El rol no se puede eliminar porque esta siendo usado por ' . $role->users->count() . ' usuario.');

        $role->delete();

        $this->emit('success', 'Rol eliminado con éxito');
    }
}
