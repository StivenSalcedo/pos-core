<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Exceptions\CustomException;
use App\Services\Factus\ApiService;
use App\Services\Factus\AppService;
use App\Services\FactusConfigurationService;
use Livewire\Component;

class Factus extends Component
{
    public $redirectUrl;
    public function mount()
    {
        $this->redirectUrl = request()->get('redirecUrl', '/administrador/payrolls');
    }

    public function render()
    {
        return view('livewire.admin.payroll.factus')->layoutData(['title' => 'Nomina Factus']);
    }

    public function getTokenFactus(): array
    {
        try {
            if (FactusConfigurationService::isApiEnabled()) {
                if (ApiService::payrollIsEnabled()) {
                    $token = AppService::getTokenFactus($this->redirectUrl);
                } else {
                    return to_route('admin.payroll.index');
                }
            } else {
                return to_route('admin.payroll.index');
            }
        } catch (\Throwable $th) {
            if ($th instanceof CustomException) {
                $this->emit('error', $th->getMessage());

                return [];
            }
            throw $th;
        }

        return $token;
    }
}
