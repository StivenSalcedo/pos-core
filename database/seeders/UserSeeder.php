<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder {

    public function run() {
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'phone' => '1234567890',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
        ]);

        $user->assignRole('administrador');

        if (App::isLocal()) {
            $user = User::create([
                'name' => 'Ivan Osorio',
                'email' => 'cajero@gmail.com',
                'phone' => '3133044553',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(10),
            ]);
        }
            
        $user->assignRole('cajero');

    }
}
