<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Adresse;
use App\Models\Ville;
use App\Models\Particulier;
use App\Models\Professionnel;
use App\Models\Departement;
use App\Models\Region;
use App\Models\Date as DateNaissance;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
            'role'   => ['required', Rule::in(['particulier', 'professionnel'])],

            'email'  => ['required', 'string', 'email', 'max:255', 'unique:utilisateur,email'],

            'password' => $this->passwordRules(),

            'pseudo'     => ['required', 'string', 'max:50'],
            'telephone'  => [
                'required',
                'string',
                'regex:/^0[1-9][0-9]{8}$/',
            ],
            'numerorue'  => ['required', 'string'],
            'nomrue'     => ['required', 'string'],
            'codepostal' => ['required', 'string', 'size:5'],
            'ville'      => ['required', 'string'],

            'civilite' => ['required_if:role,particulier', 'in:Monsieur,Madame'],
            'nom'      => ['required_if:role,particulier', 'string'],
            'prenom'   => ['required_if:role,particulier', 'string'],
            'date_naissance' => [
                'required_if:role,particulier',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
            ],

            'numsiret'        => ['nullable', 'string', 'required_if:role,professionnel'],
            'nomsociete'      => ['nullable', 'string', 'required_if:role,professionnel'],
            'secteuractivite' => ['nullable', 'string', 'required_if:role,professionnel'],
        ])->validate();


        $ville = Ville::where('nomville', strtoupper($input['ville']))
            ->where('codepostal', $input['codepostal'])
            ->first();

        if (!$ville) {
            $codePostal = $input['codepostal'];

            $depNumero = substr($codePostal, 0, 2);
            if (in_array($depNumero, ['97', '98'])) {
                $depNumero = substr($codePostal, 0, 3);
            }

            $region = Region::where('nomregion', 'INCONNUE')->first();
            if (!$region) {
                $region = new Region();
                $region->nomregion = 'INCONNUE';
                $region->save();
            }

            $departement = Departement::where('numerodepartement', $depNumero)->first();
            if (!$departement) {
                $departement = new Departement();
                $departement->idregion         = $region->idregion;
                $departement->numerodepartement = $depNumero;
                $departement->nomdepartement    = 'DEPARTEMENT ' . $depNumero;
                $departement->save();
            }

            $taxe = random_int(50, 250) / 100;

            $ville = new Ville();
            $ville->iddepartement = $departement->iddepartement;
            $ville->codepostal    = $codePostal;
            $ville->nomville      = strtoupper($input['ville']);
            $ville->taxedesejour  = $taxe;
            $ville->save();
        }

        $adresse = new Adresse();
        $adresse->idville   = $ville->idville;
        $adresse->numerorue = $input['numerorue'];
        $adresse->nomrue    = $input['nomrue'];
        $adresse->save();

        $user = new User();
        $user->idadresse          = $adresse->idadresse;
        $user->nomutilisateur     = $input['nom'] ?? ($input['nomsociete'] ?? '');
        $user->prenomutilisateur  = $input['prenom'] ?? '';
        $user->pseudonyme         = $input['pseudo'];
        $user->email              = $input['email'];
        $user->telephoneutilisateur = $input['telephone'];
        $user->solde              = 0;
        $user->password           = Hash::make($input['password']);
        $user->save();

        if ($input['role'] === 'particulier') {
            $date = new DateNaissance();
            $date->date = $input['date_naissance'];   
            $date->save();
            $particulier = new Particulier();
            $particulier->idutilisateur = $user->idutilisateur;
            $particulier->civilite      = $input['civilite'];
            $particulier->iddate        = $date->iddate;   
            $particulier->save();
        }

        if ($input['role'] === 'professionnel') {
            $professionnel = new Professionnel();
            $professionnel->idutilisateur   = $user->idutilisateur;
            $professionnel->numsiret        = $input['numsiret'];
            $professionnel->nomsociete      = $input['nomsociete'];
            $professionnel->secteuractivite = $input['secteuractivite'];
            $professionnel->save();
        }

        return $user;
    }
}
