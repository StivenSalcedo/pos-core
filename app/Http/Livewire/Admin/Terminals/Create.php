<?php

namespace App\Http\Livewire\Admin\Terminals;

use App\Models\NumberingRange;
use App\Models\Terminal;
use App\Models\User;
use App\Services\Factus\ApiService;
use App\Services\FactusConfigurationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Rules\Phone;

class Create extends Component
{
    protected $listeners = ['openCreate'];

    public $openCreate = false;

    public $ranges;

    public $factusRanges = [];

    public $usersSelected = [];

    public $name;

    public $numbering_range_id = '';

    public $factus_numbering_range_id = '';

    public $address;
    
    public $phone;
    
    public $email;

    public $isApiFactusEnabled = false;

    public function mount()
    {
        $this->getRanges();
        $this->isApiFactusEnabled = FactusConfigurationService::isApiEnabled();
    }

    public function render()
    {
        return view('livewire.admin.terminals.create');
    }

    protected function getRanges()
    {
        $ranges = NumberingRange::where('status', '0')->get();

        foreach ($ranges as $value) {
            $this->ranges[$value->id] = $value->document.'-'.$value->prefix.'('.$value->from.'-'.$value->to.')';
        }
    }

    public function openCreate()
    {
        $this->resetExcept('ranges');
        $this->resetValidation();
        $users = User::doesntHave('terminals')->select('id', 'name')->get()->pluck('name', 'id');
        $this->emit('refresh', $users);
        $this->openCreate = true;
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
                'name' => $item['document'].'-'.$item['prefix'].'('.$item['from'].'-'.$item['to'].')',
            ];
        })->pluck('name', 'id');
    }

    public function store()
    {
        $this->numbering_range_id = $this->isApiFactusEnabled ? 1 : $this->numbering_range_id;
        $this->factus_numbering_range_id = $this->isApiFactusEnabled ? $this->factus_numbering_range_id : null;

        $rules = [
            'name' => 'required|string|min:5|max:50|unique:terminals',
            'numbering_range_id' => 'required|integer|exists:numbering_ranges,id',
            'address' => 'nullable|string|max:250',
            'phone' => ['nullable', 'string', new Phone],
            'email' => 'nullable|string|email|max:250',
            'usersSelected' => 'array',
        ];

        if($this->isApiFactusEnabled)
            {
                  $rules['factus_numbering_range_id'] = 'required|integer';
            }

        $attributes = [
            'name' => 'nombre',
            'numbering_range_id' => 'rango de numeración',
            'address' => 'direccion de la sede',
            'phone' => 'contacto o celular de la sede',
            'email' => 'email de la sede',
            'factus_numbering_range_id' => 'rango de numeración',
        ];

        $data = $this->validate($rules, null, $attributes);

        $terminal = Terminal::create($data);

        Cache::forget('terminals');

        foreach ($this->usersSelected as $value) {
            if (! DB::table('terminal_user')->where('user_id', $value)->exists()) {
                $terminal->users()->attach($value);
            }
        }

        $this->resetExcept('ranges');

        $this->emit('success', 'Sede creada con éxito');
        $this->emitTo('admin.terminals.index', 'render');
        $this->emitTo('admin.menu', 'render');

    }
}
