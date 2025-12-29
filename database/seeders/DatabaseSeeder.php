<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Particulier;
use App\Models\Annonce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\RoleEnum;
use Illuminate\Support\Str;

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
            'telephoneutilisateur' => '0612345678',
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

        foreach (RoleEnum::cases() as $index => $roleEnum) {
            $role = Role::firstWhere('name', $roleEnum->value);

            $base = Str::slug($roleEnum->value, '_');

            $roleUser = User::firstOrCreate(
                ['email' => $base . '@example.com'],
                [
                    'pseudonyme' => ucfirst($base),
                    'password' => bcrypt('password'),
                    'solde' => 0.00,
                    'idadresse' => 1,
                    'iddate' => 1,
                    'telephoneutilisateur' => sprintf('0699%06d', $index),
                ],
            );

            $roleUser->assignRole($role);
        }

        $annonces = [
            [
                'titreannonce' => 'Test - Appartement vérifié',
                'descriptionannonce' => 'Annonce de test vérifiée pour le compte demo.',
                'prixnuitee' => 80,
                'estverifie' => true,
            ],
            [
                'titreannonce' => 'Test - Maison vérifiée',
                'descriptionannonce' => 'Seconde annonce vérifiée pour scénarios de test.',
                'prixnuitee' => 120,
                'estverifie' => true,
            ],
            [
                'titreannonce' => 'Test - Studio en attente',
                'descriptionannonce' => 'Annonce de test non vérifiée (statut inactif).',
                'prixnuitee' => 55,
                'estverifie' => false,
            ],
            [
                'titreannonce' => 'Test - Loft en attente',
                'descriptionannonce' => 'Seconde annonce non vérifiée pour le compte demo.',
                'prixnuitee' => 95,
                'estverifie' => false,
            ],
        ];

        foreach ($annonces as $index => $data) {
            $annonce = Annonce::create([
                'idadresse' => 51,
                'iddate' => 44155,
                'idheuredepart' => 7,
                'idtypehebergement' => 6,
                'idheurearrivee' => 48,
                'idutilisateur' => $user->idutilisateur,
                'titreannonce' => $data['titreannonce'],
                'descriptionannonce' => $data['descriptionannonce'],
                'prixnuitee' => $data['prixnuitee'],
                'minimumnuitee' => 1,
                'possibiliteanimaux' => false,
                'nombrebebesmax' => 0,
                'possibilitefumeur' => false,
                'capacite' => 2,
                'nbchambres' => 1,
                'montantacompte' => null,
                'pourcentageacompte' => null,
            ]);

            $annonce->estverifie = $data['estverifie'];
            $annonce->save();
        }
        
    }
}
