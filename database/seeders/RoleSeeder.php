<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [Role::ADMIN, 'Admin', 'Neomezený přístup ke všem částem administrace — správa uživatelů a rolí, obsahu, klubu i nastavení. Tuto roli nelze přes rozhraní přidělit ani odebrat, existuje pouze jednou.'],
            [Role::USER_MANAGER, 'Správce uživatelů', 'Přidává, upravuje a maže uživatele administrace, přiděluje jim role (kromě role Admin) a resetuje jim hesla. Spravuje také přehled odběratelů novinek.'],
            [Role::CONTENT, 'Redaktor obsahu', 'Vytváří, upravuje a maže články a novinky a spravuje jejich tagy.'],
            [Role::CLUB, 'Klub', 'Spravuje týmy, hráče a kempy — soupisky, pořadí týmů, termíny a přihlášky na kempy.'],
        ];

        foreach ($roles as [$key, $name, $description]) {
            Role::updateOrCreate(['key' => $key], ['name' => $name, 'description' => $description]);
        }
    }
}
