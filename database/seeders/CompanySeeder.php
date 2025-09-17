<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder {

    public function run() {
        Company::create([
            'nit' => '12345678-6',
            'name' => 'Halltec',
            'direction' => 'cra 10 # 9-04',
            'phone' => '3165584659',
            'email' => 'halltec@halltec.com',
            'type_bill' => '1',
            'barcode' => '0',
            'percentage_tip' => 0
        ]);
    }
}
