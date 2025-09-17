<?php

namespace App\Http\Middleware;

use App\Models\Module;
use App\Services\ModuleService;
use Closure;
use Illuminate\Http\Request;

class HasModule
{
    public function handle(Request $request, Closure $next, $module)
    {
        $result = ModuleService::exists($module);

        $user = auth()->user();

        if (!$result || !$user->hasPermissionTo($module)) {
            abort(404);
        }

        return $next($request);
    }
}
