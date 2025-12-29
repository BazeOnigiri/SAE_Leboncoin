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
use App\Services\SmsService;
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
     * Liste des secteurs d’activité (même logique que UserAccountController)
     */
    public const SECTEURS = [
        'Agroalimentaire',
        'Banque / Assurance',
        'BTP / Matériaux de construction',
        'Chimie / Parachimie',
        'Commerce / Négoce / Distribution',
        'Édition / Communication / Multimédia',
        'Énergie / Environnement',
        'Industrie pharmaceutique',
        'Informatique / Télécoms',
        'Machines et équipements / Automobile',
        'Services aux entreprises',
        'Textile / Habillement / Chaussure',
        'Tourisme - Restauration',
        'Transports / Logistique',
        'Autre secteur',
    ];

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'role'           => ['required', Rule::in(['particulier', 'professionnel'])],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:utilisateur,email'],
            'password'       => $this->passwordRules(),
            'pseudo'         => ['required', 'string', 'max:50'],
            'telephone'      => ['required', 'regex:/^0[1-9][0-9]{8}$/'],

            'numerorue'      => ['required', 'string'],
            'nomrue'         => ['required', 'string'],
            'codepostal'     => ['required', 'string', 'size:5'],
            'ville'          => ['required', 'string'],

            'civilite'       => ['exclude_unless:role,particulier', 'required', 'in:Monsieur,Madame,Non spécifié'],
            'nom'            => ['exclude_unless:role,particulier', 'required', 'string', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/'],
            'prenom'         => ['exclude_unless:role,particulier', 'required', 'string', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/'],
            'date_naissance' => [
                'exclude_unless:role,particulier',
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString()
            ],

            'numsiret'       => [
                'exclude_unless:role,professionnel',
                'required',
                'digits:14',
                'unique:professionnel,numsiret'
            ],
            'nomsociete'     => ['exclude_unless:role,professionnel', 'required', 'string', 'max:255'],
            'secteuractivite'=> [
                'exclude_unless:role,professionnel',
                'required',
                Rule::in(self::SECTEURS)
            ],
            [
                'nom.regex' => 'Le nom ne doit contenir que des lettres.',
                'prenom.regex' => 'Le prénom ne doit contenir que des lettres.',
                ]
        ])->validate();

        return DB::transaction(function () use ($input) {
            $nomVilleSaisi = strtoupper($input['ville']);
            $codePostal    = $input['codepostal'];

            $ville = Ville::where('nomville', $nomVilleSaisi)
                ->where('codepostal', $codePostal)
                ->first();

            if (!$ville) {
                $communeData = null;

                try {
                    $response = Http::timeout(5)->withoutVerifying()->get(
                        'https://geo.api.gouv.fr/communes',
                        [
                            'codePostal' => $codePostal,
                            'fields'     => 'nom,codeDepartement,codeRegion',
                            'format'     => 'json',
                        ]
                    );

                    if ($response->successful()) {
                        $communeData = collect($response->json())
                            ->first(fn ($c) => strtoupper($c['nom']) === $nomVilleSaisi)
                            ?? $response->json()[0] ?? null;
                    }
                } catch (\Throwable $e) {}

                $codeRegion = $communeData['codeRegion'] ?? 'INCONNU';
                $codeDep    = $communeData['codeDepartement'] ?? substr($codePostal, 0, 2);

                if (in_array(substr($codePostal, 0, 2), ['97', '98'])) {
                    $codeDep = substr($codePostal, 0, 3);
                }

                $region = Region::firstOrCreate(['nomregion' => 'REGION ' . $codeRegion]);

                $departement = Departement::firstOrCreate(
                    ['numerodepartement' => $codeDep],
                    [
                        'idregion'       => $region->idregion,
                        'nomdepartement' => 'DEPARTEMENT ' . $codeDep,
                    ]
                );

                $ville = Ville::firstOrCreate(
                    [
                        'codepostal' => $codePostal,
                        'nomville'   => $nomVilleSaisi,
                    ],
                    [
                        'iddepartement' => $departement->iddepartement,
                        'taxedesejour'  => random_int(50, 300) / 100,
                    ]
                );
            }

            $adresse = Adresse::firstOrCreate([
                'numerorue' => $input['numerorue'],
                'nomrue'    => $input['nomrue'],
                'idville'   => $ville->idville,
            ]);

            $creationDate = Date::firstOrCreate(['date' => now()->toDateString()]);

            $smsService = app(SmsService::class);
            $code = $smsService->generateCode();

            $user = User::create([
                'idadresse'                   => $adresse->idadresse,
                'iddate'                      => $creationDate->iddate,
                'pseudonyme'                  => $input['pseudo'],
                'email'                       => $input['email'],
                'telephoneutilisateur'        => $input['telephone'],
                'phone_verified'              => false,
                'phone_verification_code'     => $code,
                'phone_verification_expires_at'=> now()->addMinutes(10),
                'solde'                       => 0,
                'password'                    => Hash::make($input['password']),
            ]);

            $message = "Leboncoin : votre code de vérification est : {$code}. Valide 10 minutes.";
            $smsService->send($user->telephoneutilisateur, $message);


            if ($input['role'] === 'particulier') {
                $birthDate = Date::firstOrCreate(['date' => $input['date_naissance']]);

                Particulier::create([
                    'idutilisateur'   => $user->idutilisateur,
                    'nomutilisateur'  => $input['nom'],
                    'prenomutilisateur'=> $input['prenom'],
                    'civilite'        => $input['civilite'],
                    'iddate'          => $birthDate->iddate,
                ]);
            }

            if ($input['role'] === 'professionnel') {
                Professionnel::create([
                    'idutilisateur'   => $user->idutilisateur,
                    'numsiret'        => $input['numsiret'],
                    'nomsociete'      => $input['nomsociete'],
                    'secteuractivite' => $input['secteuractivite'],
                ]);
            }

            return $user;
        });
    }
}
