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
    }
}
