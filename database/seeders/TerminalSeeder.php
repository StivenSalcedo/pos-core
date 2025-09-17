<?php

namespace Database\Seeders;

use App\Models\Terminal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder {
    
    public function run() {

        $terminal = Terminal::create([
            'name' => 'Principal',
            'numbering_range_id' => 1,
        ]);

        $users = User::all();

        foreach ($users as $user) {
            $user->terminals()->attach($terminal->id);
        }

    }
}
