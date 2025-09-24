<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component {

    use LivewireTrait;

    protected $listeners = ['openEdit'];

    public $openEdit=false, $roles, $role, $user, $password, $password_confirmation;

    public function mount(){
        $this->user = new User();
        $this->roles = Role::orderBy('id', 'ASC')->get()->pluck('name', 'name');
    }

    protected function rules(){
        return  [
            'user.name' => 'required|string|min:5|max:250',
            'user.phone' => ['required', 'string', new Phone],
            'user.email' => 'required|string|email|max:250|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8|max:250|confirmed',
            'password_confirmation' => 'nullable|string|min:8|max:250',
            'user.status' => 'required|min:0|max:1',
        ];
    }

    public function render() {
        return view('livewire.admin.users.edit');
    }

    public function openEdit(User $user){
        $this->resetValidation();
        $this->openEdit = true;
        $this->user = $user;
        $this->role = $user->role;
    }

    public function update(){

        $this->applyTrim(array_keys($this->rules()));
        $this->validate();

        if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }

        if ($this->user->id === 1) {
            $this->user->status = 0;
        }

       

       // if ( $this->role !== 'Administrador' && $this->user->role !== 'Administrador'){
            $this->user->syncRoles($this->role);
       // }

        $this->user->save();

        $this->emit('success', 'Usuario actualizado con éxito');
        $this->emitTo('admin.users.index', 'render');

        $this->resetExcept('roles');
        $this->user = new User();
    }
}
