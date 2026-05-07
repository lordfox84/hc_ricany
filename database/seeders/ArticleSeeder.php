<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $articles = [
            [
                'title_cs'    => 'Vítězné tažení pokračuje — tři výhry v řadě!',
                'title_en'    => 'Winning streak continues — three wins in a row!',
                'excerpt_cs'  => 'Naši muži podali skvělý výkon v posledním kole. Výsledek 5:2 potvrdil dobrou formu celého týmu a přiblížil nás play-off.',
                'excerpt_en'  => 'Our men put in a brilliant performance in the last round. The 5:2 result confirmed the good form of the whole team.',
                'category_cs' => 'Zápas',
                'category_en' => 'Match',
                'is_featured' => true,
                'is_published'=> true,
                'published_at'=> now()->subDays(2),
            ],
            [
                'title_cs'    => 'Dorost slaví postup do semifinále',
                'title_en'    => 'Juniors celebrate semi-final promotion',
                'excerpt_cs'  => 'Naše dorostenecká kategorie se probojovala do semifinále krajského přeboru. Výborná zpráva pro celý klub!',
                'excerpt_en'  => 'Our junior category has fought through to the semi-final of the regional championship.',
                'category_cs' => 'Mládež',
                'category_en' => 'Youth',
                'is_featured' => false,
                'is_published'=> true,
                'published_at'=> now()->subDays(7),
            ],
            [
                'title_cs'    => 'Otevírací dny přihlášek na novou sezonu',
                'title_en'    => 'Registration open days for new season',
                'excerpt_cs'  => 'Přihlášky na sezonu 2025/26 jsou otevřeny pro všechny věkové kategorie od 5 let.',
                'excerpt_en'  => 'Registrations for the 2025/26 season are open for all age categories from 5 years old.',
                'category_cs' => 'Klub',
                'category_en' => 'Club',
                'is_featured' => false,
                'is_published'=> true,
                'published_at'=> now()->subDays(12),
            ],
            [
                'title_cs'    => 'Nový kondicionér posiluje tým',
                'title_en'    => 'New fitness coach strengthens the team',
                'excerpt_cs'  => 'Do realizačního týmu přichází zkušený kondicionér Jan Novák, který pracoval s prvoligovými celky.',
                'excerpt_en'  => 'Experienced fitness coach Jan Novák joins the coaching staff, having worked with top-flight clubs.',
                'category_cs' => 'Trénink',
                'category_en' => 'Training',
                'is_featured' => false,
                'is_published'=> true,
                'published_at'=> now()->subDays(16),
            ],
        ];

        foreach ($articles as $data) {
            $data['user_id'] = $admin?->id;
            Article::create($data);
        }
    }
}
