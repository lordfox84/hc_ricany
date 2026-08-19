<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Admin HC Říčany',
            'email'    => 'admin@hcricany.cz',
            'password' => Hash::make('heslo123'),
        ]);

        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            ArticleSeeder::class,
            TeamSeeder::class,
        ]);

        $admin->roles()->syncWithoutDetaching(Role::where('key', Role::ADMIN)->first());
    }
}
