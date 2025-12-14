<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Adresse;
use App\Models\Ville;
use App\Models\Particulier;
use App\Models\Professionnel;
use App\Models\Departement;
use App\Models\Region;
use App\Models\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

        /**
         * Validate and create a newly registered user.
         *
         * @param  array<string, string>  $input
         */
    public function create(array $input): User
    {
        Validator::make($input, [
            'role' => ['required', Rule::in(['particulier', 'professionnel'])],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:utilisateur,email'],
            'password' => $this->passwordRules(),
            'pseudo' => ['required', 'string', 'max:50'],
            'telephone' => ['required', 'string', 'regex:/^0[1-9][0-9]{8}$/'],
            'numerorue' => ['required', 'string'],
            'nomrue' => ['required', 'string'],
            'codepostal' => ['required', 'string', 'size:5'],
            'ville' => ['required', 'string'],
            'civilite' => ['exclude_unless:role,particulier', 'required', 'in:Monsieur,Madame'],
            'nom' => ['exclude_unless:role,particulier', 'required', 'string', 'max:50'],
            'prenom' => ['exclude_unless:role,particulier', 'required', 'string', 'max:50'],
            'date_naissance' => ['exclude_unless:role,particulier', 'required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'numsiret' => ['exclude_unless:role,professionnel', 'required', 'string', 'size:14', 'unique:professionnel,numsiret'],
            'nomsociete' => ['exclude_unless:role,professionnel', 'required', 'string', 'max:255'],
            'secteuractivite' => ['exclude_unless:role,professionnel', 'required', 'string', 'max:100'],
        ])->validate();

        return DB::transaction(function () use ($input) {
            $nomVilleSaisi = strtoupper($input['ville']);
            $codePostal = $input['codepostal'];

            $ville = Ville::where('nomville', $nomVilleSaisi)
                ->where('codepostal', $codePostal)
                ->first();

            if (!$ville) {
                $communeData = null;
                try {
                    $response = Http::timeout(5)->withoutVerifying()->get('https://geo.api.gouv.fr/communes', [
                        'codePostal' => $codePostal,
                        'fields' => 'nom,code,codesPostaux,codeDepartement,codeRegion,departement,region',
                        'format' => 'json',
                    ]);

                    if ($response->successful()) {
                        $communes = $response->json();
                        foreach ($communes as $commune) {
                            if (strtoupper($commune['nom']) === $nomVilleSaisi) {
                                $communeData = $commune;
                                break;
                            }
                        }
                        if (!$communeData && !empty($communes)) {
                            $communeData = $communes[0];
                        }
                    }
                } catch (\Throwable $e) {
                }

                $codeRegion = $communeData['codeRegion'] ?? 'INCONNU';
                $codeDep = $communeData['codeDepartement'] ?? substr($codePostal, 0, 2);
                
                if (in_array(substr($codePostal, 0, 2), ['97', '98'])) {
                     $codeDep = substr($codePostal, 0, 3);
                }

                $region = Region::firstOrCreate(['nomregion' => 'REGION ' . $codeRegion]);
                
                $departement = Departement::firstOrCreate(
                    ['numerodepartement' => $codeDep],
                    ['idregion' => $region->idregion, 'nomdepartement' => 'DEPARTEMENT ' . $codeDep]
                );

                $ville = Ville::firstOrCreate(
                    [
                        'codepostal' => $codePostal,
                        'nomville' => $nomVilleSaisi
                    ],
                    [
                        'iddepartement' => $departement->iddepartement,
                        'taxedesejour' => random_int(50, 300) / 100
                    ]
                );
            }

            $adresse = Adresse::firstOrCreate([
                'numerorue' => $input['numerorue'],
                'nomrue' => $input['nomrue'],
                'idville' => $ville->idville,
            ]);

            $creationDate = Date::firstOrCreate(['date' => now()->toDateString()]);

            $user = new User();
            $user->idadresse = $adresse->idadresse;
            $user->iddate = $creationDate->iddate;
            $user->pseudonyme = $input['pseudo'];
            $user->email = $input['email'];
            $user->telephoneutilisateur = $input['telephone'];
            $user->solde = 0;
            $user->password = Hash::make($input['password']);
            
            // Assignation des rôles Spatie (si le package est installé)
            // $user->assignRole($input['role']); // Décommente si tu utilises Spatie Role
            
            $user->save();

            if ($input['role'] === 'particulier') {
                $birthDate = Date::firstOrCreate(['date' => $input['date_naissance']]);
                $particulier = new Particulier();
                $particulier->idutilisateur = $user->idutilisateur;
                $particulier->nomutilisateur = $input['nom'];
                $particulier->prenomutilisateur = $input['prenom'];
                $particulier->civilite = $input['civilite'];
                $particulier->iddate = $birthDate->iddate;
                $particulier->save();
            } elseif ($input['role'] === 'professionnel') {
                $professionnel = new Professionnel();
                $professionnel->idutilisateur = $user->idutilisateur;
                $professionnel->numsiret = $input['numsiret'];
                $professionnel->nomsociete = $input['nomsociete'];
                $professionnel->secteuractivite = $input['secteuractivite'];
                $professionnel->save();
            }

            return $user;
        });
    }
}