<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $team = Team::where('slug', 'starsi-zaci')->first();
        if (!$team) return;

        $players = [
            ['first_name' => 'Jakub',    'last_name' => 'Novák',      'jersey_number' => 1,  'position' => 'Brankář',  'hand' => 'Pravák', 'date_of_birth' => '2012-03-14'],
            ['first_name' => 'Martin',   'last_name' => 'Dvořák',     'jersey_number' => 2,  'position' => 'Obránce',  'hand' => 'Pravák', 'date_of_birth' => '2012-07-22'],
            ['first_name' => 'Tomáš',    'last_name' => 'Procházka',  'jersey_number' => 4,  'position' => 'Obránce',  'hand' => 'Levák',  'date_of_birth' => '2011-11-05'],
            ['first_name' => 'Lukáš',    'last_name' => 'Krejčí',     'jersey_number' => 7,  'position' => 'Útočník',  'hand' => 'Pravák', 'date_of_birth' => '2012-01-30'],
            ['first_name' => 'Ondřej',   'last_name' => 'Blažek',     'jersey_number' => 9,  'position' => 'Útočník',  'hand' => 'Levák',  'date_of_birth' => '2011-09-18'],
            ['first_name' => 'Pavel',    'last_name' => 'Horák',      'jersey_number' => 11, 'position' => 'Útočník',  'hand' => 'Pravák', 'date_of_birth' => '2012-05-27'],
            ['first_name' => 'Marek',    'last_name' => 'Šimánek',    'jersey_number' => 14, 'position' => 'Obránce',  'hand' => 'Levák',  'date_of_birth' => '2011-12-03'],
            ['first_name' => 'Filip',    'last_name' => 'Čermák',     'jersey_number' => 16, 'position' => 'Útočník',  'hand' => 'Pravák', 'date_of_birth' => '2012-08-11'],
            ['first_name' => 'Adam',     'last_name' => 'Veselý',     'jersey_number' => 17, 'position' => 'Útočník',  'hand' => 'Levák',  'date_of_birth' => '2011-04-09'],
            ['first_name' => 'Jan',      'last_name' => 'Pokorný',    'jersey_number' => 19, 'position' => 'Obránce',  'hand' => 'Pravák', 'date_of_birth' => '2012-02-16'],
            ['first_name' => 'Vojtěch',  'last_name' => 'Růžička',    'jersey_number' => 21, 'position' => 'Útočník',  'hand' => 'Levák',  'date_of_birth' => '2011-06-24'],
            ['first_name' => 'Michal',   'last_name' => 'Kratochvíl', 'jersey_number' => 23, 'position' => 'Útočník',  'hand' => 'Pravák', 'date_of_birth' => '2012-10-07'],
            ['first_name' => 'Radek',    'last_name' => 'Beneš',      'jersey_number' => 30, 'position' => 'Brankář',  'hand' => 'Levák',  'date_of_birth' => '2011-08-19'],
        ];

        // Pravatar.cc má 70 číslovných avatarů
        $avatarIndexes = [10, 12, 15, 17, 20, 22, 25, 27, 30, 33, 36, 40, 44];

        foreach ($players as $i => $data) {
            $avatarNum = $avatarIndexes[$i];
            $url       = "https://i.pravatar.cc/300?img={$avatarNum}";
            $contents  = @file_get_contents($url);
            $path      = null;

            if ($contents !== false) {
                $filename = 'players/seeder_' . $avatarNum . '.jpg';
                Storage::disk('public')->put($filename, $contents);
                $path = $filename;
            }

            $data['team_id']    = $team->id;
            $data['name']       = $data['first_name'] . ' ' . $data['last_name'];
            $data['photo']      = $path;
            $data['is_active']  = true;
            $data['sort_order'] = $i;

            Player::create($data);
        }
    }
}
