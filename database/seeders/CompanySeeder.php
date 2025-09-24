<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder {

    public function run() {
        Company::create([
            'nit' => '12345678-6',
            'name' => 'Comapañia',
            'direction' => 'calle 48 p bis',
            'phone' => '3144521111',
            'email' => 'test@test.com',
            'type_bill' => '1',
            'barcode' => '0',
            'percentage_tip' => 0
        ]);
    }
}
