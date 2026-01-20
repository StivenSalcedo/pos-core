<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddUsersShowAllTerminals extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Permission::firstOrCreate([
            'name' => 'ver todas las sedes',
            'guard_name' => 'web',
        ]);
    }
}
