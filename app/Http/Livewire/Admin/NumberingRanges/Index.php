<?php

namespace App\Http\Livewire\Admin\NumberingRanges;

use App\Models\NumberingRange;
use Livewire\Component;

class Index extends Component {

    protected $listeners=['render'];

    public function render() {

        $ranges = NumberingRange::all();

        return view('livewire.admin.numbering-ranges.index', compact('ranges'))->layoutData(['title' => 'Rangos de numeración']);
    }
}
