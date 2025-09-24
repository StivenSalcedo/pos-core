<?php

namespace App\Http\Livewire\Admin\Terminals;

use App\Models\NumberingRange;
use App\Models\Terminal;
use App\Models\User;
use App\Services\Factus\ApiService;
use App\Rules\Phone;
use App\Services\FactusConfigurationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class Edit extends Component
{
    protected $listeners = ['openEdit'];

    public $ranges;

    public $factusRanges = [];

    public $openEdit = false;

    public $usersSelected = [];

    public $terminal;

    public $isApiFactusEnabled = false;

    protected $validationAttributes = [
        'name' => 'nombre',
        'numbering_range_id' => 'rango de numeración',
        'factus_numbering_range_id' => 'rango de numeración',
    ];

    public function mount()
    {
        $this->terminal = new Terminal();
        $this->getRanges();
    }

    protected function rules()
    {
        return [
            'terminal.name' => 'required|string|min:5|max:50|unique:terminals,name,'.$this->terminal->id,
            'terminal.numbering_range_id' => 'required|integer|exists:numbering_ranges,id',
            'terminal.address' => 'nullable|string|max:250',
            'terminal.phone' => ['nullable', 'string', new Phone],
            'terminal.email' => 'nullable|string|email|max:250',
            'terminal.factus_numbering_range_id' => $this->isApiFactusEnabled ? 'required|integer' : 'nullable',
            'terminal.status' => 'required|integer|min:0|max:1',
            'usersSelected' => 'array',
        ];
    }

    public function render()
    {
        return view('livewire.admin.terminals.edit');
    }

    protected function getRanges()
    {
        $ranges = NumberingRange::where('status', '0')->get();

        foreach ($ranges as $value) {
            $this->ranges[$value->id] = $value->prefix.'('.$value->from.'-'.$value->to.')';
        }
    }

    public function openEdit(Terminal $terminal)
    {
        $this->resetExcept('ranges');
        $this->resetValidation();
        $users = $this->getUsers($terminal);
        $this->emit('refresh-edit', $users);
        $this->openEdit = true;
        $this->terminal = $terminal;
        $this->isApiFactusEnabled = FactusConfigurationService::isApiEnabled();
        $this->getFactusRanges();
    }

    protected function getFactusRanges()
    {
        if (! $this->isApiFactusEnabled) {
            return [];
        }

        $this->factusRanges = collect(ApiService::numberingRanges())->transform(function ($item) {
            return [
                'id' => $item['id'],
                'name' =>$item['document'].'-'.$item['prefix'].'('.$item['from'].'-'.$item['to'].')',
            ];
        })->pluck('name', 'id');
    }

    protected function getUsers($terminal)
    {
        $array = [];
        $users = User::doesntHave('terminals')->select('id', 'name')->get();
        $users2 = $terminal->users;

        foreach ($users as $value) {
            $array[$value->id] = $value->name;
        }

        foreach ($users2 as $value) {
            $array[$value->id] = $value->name;
        }

        $this->usersSelected = $users2->pluck('id')->toArray();

        return $array;
    }

    public function update()
    {
        $this->validate();

        $this->terminal->save();

        Cache::forget('terminals');

        $this->terminal->users()->detach();

        foreach ($this->usersSelected as $value) {
            if (! DB::table('terminal_user')->where('user_id', $value)->exists()) {
                $this->terminal->users()->attach($value);
            }
        }

        $this->resetExcept('ranges');
        $this->terminal = new Terminal();

        $this->emit('success', 'Terminal actualizada con éxito');
        $this->emitTo('admin.terminals.index', 'render');
        $this->emitTo('admin.menu', 'render');

    }
}
