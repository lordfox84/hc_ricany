<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_cs' => 'Novinky',  'name_en' => 'News',     'color' => '#2abfbf', 'slug' => 'novinky'],
            ['name_cs' => 'Zápas',    'name_en' => 'Match',    'color' => '#e74c3c', 'slug' => 'zapas'],
            ['name_cs' => 'Mládež',   'name_en' => 'Youth',    'color' => '#f39c12', 'slug' => 'mladez'],
            ['name_cs' => 'Klub',     'name_en' => 'Club',     'color' => '#9b59b6', 'slug' => 'klub'],
            ['name_cs' => 'Trénink',  'name_en' => 'Training', 'color' => '#27ae60', 'slug' => 'trenink'],
            ['name_cs' => 'Akce',     'name_en' => 'Event',    'color' => '#3498db', 'slug' => 'akce'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
