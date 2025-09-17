<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use App\Services\ModuleService;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    public function isEnabled(User $user, string $name)
    {
        $result = ModuleService::exists($name);

        if ($result && $user->hasPermissionTo($name)) {
            return true;
        }

        return false;
    }
}
