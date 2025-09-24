<?php

namespace App\Http\Livewire\Admin\Users;

use App\Http\Controllers\Log;
use App\Models\User;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component {

    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $roles, $openCreate=false;

    public $name, $phone, $role='', $email, $password, $password_confirmation;

    public function mount(){
        $this->roles = Role::orderBy('id', 'ASC')->get()->pluck('name', 'name');
    }

    public function render() {
        return view('livewire.admin.users.create');
    }

    public function openCreate() {
        $this->resetValidation();
        $this->openCreate = true;
    }

    public function store(){

        if ($this->role === 'Administrador') $this->role = '';

        $rules = [
            'name' => 'required|string|min:5|max:250',
            'phone' => ['required', 'string', new Phone],
            'email' => 'required|string|email|max:250|unique:users',
            'role' => 'required|string|exists:roles,name',
            'password' => 'required|string|min:8|max:250|confirmed',
            'password_confirmation' => 'required|string|min:8|max:250',
        ];

        $this->applyTrim(array_keys($rules));

        $data = $this->validate($rules);

        try {
            DB::beginTransaction();

            $data['password'] = bcrypt($this->password);
            $user = User::create($data);

            $user->assignRole($this->role);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), $data);
            return $this->emit('error', 'Ha ocurrido un error al registrar el usuario. Vuelve a intentarlo');
        }

        $this->emit('success', 'Usuario creado con éxito');
        $this->emitTo('admin.users.index', 'render');

        $this->resetExcept('roles');

    }
}
