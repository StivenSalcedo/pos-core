<?php

namespace App\Http\Livewire\Admin;

use App\Exceptions\CustomException;
use App\Services\Factus\AppService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Menu extends Component
{
    protected $listeners = ['render' => 'refresh'];

    public function render()
    {
        return view('livewire.admin.menu');
    }

    public function refresh() {}

    public function openCashRegister()
    {
        $data = [
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'datetime' => now()->format('d-m-y H:i:s'),
        ];

        $this->deleteRegisters();

        Log::channel('cashRegister')->info('Apertura de caja', $data);

        return $this->dispatchBrowserEvent('open-cash-register');
    }

    public function getTokenFactus(): array
    {
        try {
            $token = AppService::getTokenFactus('/administrador/dashboard');
        } catch (\Throwable $th) {
            if ($th instanceof CustomException) {
                $this->emit('error', $th->getMessage());
            } else {
                $this->emit('error', 'Ha ocurrido un error inesperado al abrir Factus');
                return [];
            }
        }

        return $token;
    }

    protected function deleteRegisters()
    {
        $logFilePath = storage_path('logs/cashRegister.log');

        if (! file_exists($logFilePath)) {
            return;
        }

        $logContent = file_get_contents($logFilePath);

        $fechaActualMenos15Dias = date('Y-m-d H:i:s', strtotime('-15 days'));

        $logLines = explode(PHP_EOL, $logContent);

        $newLogLines = array_filter($logLines, function ($line) use ($fechaActualMenos15Dias) {
            if (strpos($line, 'local.INFO:') !== false) {
                preg_match('/\[(.*?)\]/', $line, $matches);
                $fechaLinea = $matches[1];

                return strtotime($fechaLinea) > strtotime($fechaActualMenos15Dias);
            }

            return true;
        });

        $newLogContent = implode(PHP_EOL, $newLogLines);

        file_put_contents($logFilePath, $newLogContent);
    }
}
