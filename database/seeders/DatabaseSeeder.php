<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name'     => 'Admin HC Říčany',
            'email'    => 'admin@hcricany.cz',
            'password' => Hash::make('heslo123'),
        ]);

        $this->call(ArticleSeeder::class);
    }
}
