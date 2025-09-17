<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company as ModelsCompany;
use Illuminate\Http\Request;

class Company extends Controller {

    public function show(){
        return ModelsCompany::first();
    }

    public function update(Request $request){
        $rules = [
            'logo' => 'nullable|image|mimes:png|max:512|dimensions:max_width=500,max_height=250',
            'company.nit' => 'required|string|max:15',
            'company.name' => 'required|string|max:150',
            'company.direction' => 'nullable|string|max:150',
            'company.phone' => 'nullable|string|max:150',
            'company.email' => 'nullable|string|email|max:150',
            'company.type_bill' => 'required|min:0|max:1',
            'company.barcode' => 'required|min:0|max:1',
        ];

        $request->validate($rules);

        
    }
    
}
