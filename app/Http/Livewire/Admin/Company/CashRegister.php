<?php

namespace App\Http\Livewire\Admin\Company;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class CashRegister extends Component
{
    public $show = false;

    public $data = [];

    public function render()
    {
        return view('livewire.admin.company.cash-register');
    }

    public function openModal()
    {
        $this->show = true;
        $this->data = $this->readLogFile();;
    }

    public function readLogFile()
    {
        $filePath = storage_path('logs/cashRegister.log');

        $fileContents = File::get($filePath);

        $pattern = '/\{.*?\}/';

        preg_match_all($pattern, $fileContents, $matches);

        $data = $matches[0];

        $decodedData = array_map(function ($item) {
            return json_decode($item, true);
        }, $data);

        return array_reverse($decodedData);
    }
}
