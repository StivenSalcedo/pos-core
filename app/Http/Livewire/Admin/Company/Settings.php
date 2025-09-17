<?php

namespace App\Http\Livewire\Admin\Company;

use App\Models\Company;
use App\Traits\LivewireTrait;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component {

    use LivewireTrait, WithFileUploads;

    public $company;

    public $preLogo, $logo, $nit, $name, $direction, $phone, $email;

    public function mount(){
        if ($this->updateOrCreate()) {
            $this->company = Company::first();
        }else{
            $this->company = new Company();
        }
    }

    protected function rules(){
        return [
            'logo' => 'nullable|image|mimes:png|max:512|dimensions:max_width=500,max_height=250',
            'company.nit' => 'required|string|max:15',
            'company.name' => 'required|string|max:150',
            'company.direction' => 'nullable|string|max:150',
            'company.phone' => 'nullable|string|max:150',
            'company.email' => 'nullable|string|email|max:150',
        ];
    }

    public function render(){
        return view('livewire.admin.company.settings')->layoutData(['title' => 'Configuración']);
    }

    public function updatedPreLogo($value){
        $this->validate(['preLogo' => 'required|image|mimes:png|max:512|dimensions:max_width=500,max_height=250']);
        $this->logo = $value;
    }

    public function getUrlLogo(){
        if ($this->logo) {
            return $this->logo->temporaryUrl();
        }else if (Storage::exists('public/images/logos/logo.png')) {
            return Storage::url('images/logos/logo.png');
        }else{
            return Storage::url('images/system/logo-default.png');
        }
    }

    public function updateOrCreate(){
        return (bool) Company::count();
    }

    public function logoExists(){
        return Storage::exists('public/images/logos/logo.png');
    }

    public function store(){
        $rules = [
            'logo' => 'nullable|image|mimes:png|max:512|dimensions:max_width=500,max_height=250',
            'nit' => 'required|string|max:15',
            'name' => 'required|string|max:150',
            'direction' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:150',
            'email' => 'nullable|string|email|max:150',
        ];

        $rules2 = $rules;
        unset($rules2['logo']);

        $this->applyTrim(array_keys($rules2));

        $data = $this->validate($rules);

        unset($data['logo']);

        if ($this->logo) {
            $this->logo->storeAs('public/images/logos', 'logo.png');
        }

        $this->company = Company::create($data);

        $this->reset('logo');

        return $this->emit('success', 'Información guardada con éxito');
    }

    public function update(){
        $rules = $this->rules();
        unset($rules['logo']);

        $this->applyTrim(array_keys($rules));
        $this->validate();

        if ($this->logo) {
            $this->logo->storeAs('public/images/logos', 'logo.png');
        }

        $this->company->save();

        $this->reset('logo');
        return $this->emit('success', 'Información de la empresa actualizada con éxito');
    }
}
