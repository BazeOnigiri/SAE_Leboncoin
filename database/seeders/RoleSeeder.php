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

        Permision::create([
            'name' => 'user.verifID',
        ])->assignRole(
            Role::firstWhere('name', RoleEnum::SERVICE_PETITE_ANNONCE->value),
            Role::firstWhere('name', RoleEnum::DIRECTEUR_SERVICE_PETITE_ANNONCE->value),
        );

        Permision::create([
            'name' => 'annonce.status',
        ])->assignRole(
            ...Role::all()
        );

        Permision::create([
            'name' => 'annonce.toggleSms',
        ])->assignRole(
            Role::firstWhere('name', RoleEnum::SERVICE_PETITE_ANNONCE->value),
            Role::firstWhere('name', RoleEnum::DIRECTEUR_SERVICE_PETITE_ANNONCE->value),
        );

        Permision::create([
            'name' => 'annonce.immobilier',
        ])->assignRole(
            Role::firstWhere('name', RoleEnum::SERVICE_IMOBILIER->value),
            Role::firstWhere('name', RoleEnum::DIRECTEUR_SERVICE_IMOBILIER->value),
        );

        Permision::create([
            'name' => 'service.inscription',
        ])->assignRole(
            Role::firstWhere('name', RoleEnum::SERVICE_INSCRIPTION->value),
            Role::firstWhere('name', RoleEnum::DIRECTEUR_SERVICE_INSCRIPTION->value),
        );
    }
}
