<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Human Operator',   'email' => 'human@test.com'],
            ['name' => 'Xeno Trader',      'email' => 'xeno@test.com'],
            ['name' => 'Automaton Core',   'email' => 'bot@test.com'],
            ['name' => 'Warlord K-Null',   'email' => 'null@test.com'],

            ['name' => 'Aqua Mind',        'email' => 'aqua@test.com'],
            ['name' => 'Hive Speaker',     'email' => 'hive@test.com'],
            ['name' => 'Void Drifter',     'email' => 'void@test.com'],
            ['name' => 'Forge Unit',       'email' => 'forge@test.com'],
            ['name' => 'Oracle of Glass',  'email' => 'oracle@test.com'],
            ['name' => 'Solar Raider',     'email' => 'raider@test.com'],
        ];

        foreach ($users as $u) {
            // updateOrCreate avoids duplicate key errors forever
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'), // known login password
                ]
            );
        }
    }
}
