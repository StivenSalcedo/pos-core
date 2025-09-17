<?php

namespace App\Http\Livewire\Admin\Terminals;

use App\Models\Terminal;
use App\Services\Factus\ApiService;
use App\Services\FactusConfigurationService;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render()
    {
        $terminals = Terminal::all();
        $isApiFactusEnabled = FactusConfigurationService::isApiEnabled();

        $factusRanges = $this->getFactusRanges($isApiFactusEnabled);

        $terminals = $terminals->transform(function ($item) use ($isApiFactusEnabled, $factusRanges) {
            if ($isApiFactusEnabled && $item->factus_numbering_range_id) {
                $item->numbering_range_name = $factusRanges[$item->factus_numbering_range_id] ?? 'No se encontró el rango';
            } else {
                $item->numbering_range_name = $item->numberingRange->prefix.'('.$item->numberingRange->from.'-'.$item->numberingRange->to.')';
            }

            return $item;
        });

        return view('livewire.admin.terminals.index', compact('terminals'));
    }

    protected function getFactusRanges($isApiFactusEnabled)
    {
        if (! $isApiFactusEnabled) {
            return [];
        }

        return collect(ApiService::numberingRanges())->transform(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['prefix'].'('.$item['from'].'-'.$item['to'].')',
            ];
        })->pluck('name', 'id');
    }
}
