<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            ['name_cs' => 'Dorost',        'name_en' => 'Juniors',      'slug' => 'dorost',        'age_group' => 'U18', 'color' => '#e74c3c', 'sort_order' => 1],
            ['name_cs' => 'Starší žáci',   'name_en' => 'Cadets',       'slug' => 'starsi-zaci',   'age_group' => 'U14', 'color' => '#f39c12', 'sort_order' => 2],
            ['name_cs' => 'Mladší žáci',   'name_en' => 'Young Cadets', 'slug' => 'mladsi-zaci',   'age_group' => 'U12', 'color' => '#27ae60', 'sort_order' => 3],
            ['name_cs' => 'Přípravka',     'name_en' => 'Prep',         'slug' => 'pripravka',     'age_group' => 'U8–U10', 'color' => '#3498db', 'sort_order' => 4],
        ];

        foreach ($teams as $data) {
            Team::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
