<?php

namespace Database\Seeders;

use App\Models\Digimon\Digimon;
use App\Models\DigimonEgg;
use Illuminate\Database\Seeder;

class DigimonEggsTableSeeder extends Seeder
{
    public function run()
    {
        $eggs = [
            [
                'name' => 'Digital Monster Ver.1',
                'starter_digimon_id' => Digimon::where('name', 'Botamon')->first()->id,
            ],
        ];

        foreach ($eggs as $egg) {
            $dbEgg = DigimonEgg::firstWhere('starter_digimon_id', $egg['starter_digimon_id']);
            if ($dbEgg === null) {
                DigimonEgg::create([
                    'name' => $egg['name'],
                    'starter_digimon_id' => $egg['starter_digimon_id'],
                ]);
            }
        }
    }
}
