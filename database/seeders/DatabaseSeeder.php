<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Particulier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(InsertSeeder::class);
        $user = User::create([
            'nomutilisateur' => 'TestNom',
            'prenomutilisateur' => 'TestPrenom',
            'pseudonyme' => 'TestPseudo',
            'password' => bcrypt('password'),
            'email' => 'test@example.com',
            'solde' => 67.00,
            'idadresse' => 1,
        ]);

        Particulier::create([
            'idutilisateur' => $user->idutilisateur, 
            'civilite' => 'Monsieur',
            'iddate' => 30200,
        ]);
    }
}
