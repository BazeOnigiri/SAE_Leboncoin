<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Particulier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\RoleEnum;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(InsertSeeder::class);
        $this->call(RoleSeeder::class);

        $user = User::create([
            'pseudonyme' => 'TestPseudo',
            'password' => bcrypt('passwordT67!'),
            'email' => 'test@example.com',
            'solde' => 67.00,
            'idadresse' => 1,
            'iddate' => 1,
        ]);
        
        Particulier::create([
            'iddate' => 1,
            'idutilisateur' => $user->idutilisateur,
            'nomutilisateur' => 'TestNom',
            'prenomutilisateur' => 'TestPrenom', 
            'civilite' => 'Monsieur',
        ]);

        $servicePA = Role::firstWhere('name', RoleEnum::SERVICE_PETITE_ANNONCE->value);

        User::create([
            'pseudonyme' => 'Greg Petite Annonce',
            'password' => bcrypt('passwordT67!'),
            'email' => 'greg@example.com',
            'solde' => 00.00,
            'idadresse' => 1,
            'iddate' => 1,
        ])->assignRole($servicePA);
    }
}
