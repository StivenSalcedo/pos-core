<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class Index extends Component {

    protected $listeners = ['render'];

    public function render() {

        $users = User::all();

        return view('livewire.admin.users.index', compact('users'))->layoutData(['title' => 'Usuarios']);
    }
}
