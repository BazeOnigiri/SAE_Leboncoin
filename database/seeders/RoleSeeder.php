<?php

namespace Database\Seeders;

use App\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission as Permision;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RoleEnum::cases() as $roleEnum) {
            Role::create([
                'name' => $roleEnum->value,
            ]);
        }

        (Permision::create([
            'name' => 'annonces.verif',
        ]))->assignRole(
            Role::firstWhere('name', RoleEnum::SERVICE_PETITE_ANNONCE->value),
            Role::firstWhere('name', RoleEnum::DIRECTEUR_SERVICE_PETITE_ANNONCE->value),
        );
    }
}
