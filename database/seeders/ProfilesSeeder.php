<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class ProfilesSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
        'human@test.com' => [
        'name' => 'Operator R-17',
        'species' => 'Human',
        'atmosphere' => 'O2',
        'gravity' => 'Standard',
        'tempMin' => -10,
        'tempMax' => 35,
        'comms' => 'Radio',
        'intent' => 'Alliance',
        'bioType' => 'Organic',
        'risk' => 55,
    ],

    'xeno@test.com' => [
        'name' => 'Xeno Trader “Murk”',
        'species' => 'Xeno',
        'atmosphere' => 'Methane',
        'gravity' => 'Low',
        'tempMin' => -140,
        'tempMax' => -60,
        'comms' => 'Pheromones',
        'intent' => 'Trade',
        'bioType' => 'Organic',
        'risk' => 40,
    ],

    'bot@test.com' => [
        'name' => 'Automaton Core S-9',
        'species' => 'Automaton',
        'atmosphere' => 'Vacuum',
        'gravity' => 'High',
        'tempMin' => -200,
        'tempMax' => 120,
        'comms' => 'Text',
        'intent' => 'Alliance',
        'bioType' => 'Mechanical',
        'risk' => 20,
    ],

    'null@test.com' => [
        'name' => 'Warlord K-Null',
        'species' => 'Hybrid',
        'atmosphere' => 'O2',
        'gravity' => 'Standard',
        'tempMin' => 0,
        'tempMax' => 50,
        'comms' => 'Radio',
        'intent' => 'Conquest',
        'bioType' => 'Mechanical',
        'risk' => 95,
    ],

    'aqua@test.com' => [
        'name' => 'Aqua Mind L-3',
        'species' => 'Xeno',
        'atmosphere' => 'O2',
        'gravity' => 'Low',
        'tempMin' => 5,
        'tempMax' => 25,
        'comms' => 'Telepathy',
        'intent' => 'Romance',
        'bioType' => 'Organic',
        'risk' => 15,
    ],

    'hive@test.com' => [
        'name' => 'Hive Speaker Θ',
        'species' => 'Hybrid',
        'atmosphere' => 'Methane',
        'gravity' => 'Standard',
        'tempMin' => -80,
        'tempMax' => 10,
        'comms' => 'Pheromones',
        'intent' => 'Alliance',
        'bioType' => 'Organic',
        'risk' => 35,
    ],

    'void@test.com' => [
        'name' => 'Void Drifter X',
        'species' => 'Human',
        'atmosphere' => 'Vacuum',
        'gravity' => 'Low',
        'tempMin' => -120,
        'tempMax' => 60,
        'comms' => 'Text',
        'intent' => 'Trade',
        'bioType' => 'Organic',
        'risk' => 65,
    ],

    'forge@test.com' => [
        'name' => 'Forge Unit A-12',
        'species' => 'Automaton',
        'atmosphere' => 'O2',
        'gravity' => 'High',
        'tempMin' => -50,
        'tempMax' => 300,
        'comms' => 'Radio',
        'intent' => 'Trade',
        'bioType' => 'Mechanical',
        'risk' => 30,
    ],

    'oracle@test.com' => [
        'name' => 'Oracle of Glass',
        'species' => 'Xeno',
        'atmosphere' => 'O2',
        'gravity' => 'Standard',
        'tempMin' => -20,
        'tempMax' => 40,
        'comms' => 'Light',
        'intent' => 'Romance',
        'bioType' => 'Organic',
        'risk' => 25,
    ],

    'raider@test.com' => [
        'name' => 'Solar Raider ZK',
        'species' => 'Hybrid',
        'atmosphere' => 'O2',
        'gravity' => 'High',
        'tempMin' => 20,
        'tempMax' => 90,
        'comms' => 'Radio',
        'intent' => 'Conquest',
        'bioType' => 'Mechanical',
        'risk' => 85,
    ],
        ];

        foreach ($map as $email => $profileData) {
            $user = User::where('email', $email)->first();
            if (!$user) continue;

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($profileData, ['user_id' => $user->id])
            );
        }
    }
}
