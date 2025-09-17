<?php

namespace App\Http\Middleware;

use App\Services\Factus\ApiService;
use App\Services\FactusConfigurationService;
use Closure;
use Illuminate\Http\Request;

class CheckPayrollFactus
{
    public function handle(Request $request, Closure $next)
    {
        if (FactusConfigurationService::isApiEnabled()) {
            if (ApiService::payrollIsEnabled()) {
                if ($request->routeIs('admin.staff.index')) {
                    return to_route('admin.payroll.factus', ['redirecUrl' => '/administrador/staff']);
                }
                return to_route('admin.payroll.factus', ['redirecUrl' => '/administrador/payrolls']);
            }
        }
        return $next($request);
    }
}
