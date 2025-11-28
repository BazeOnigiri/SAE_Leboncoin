<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Particulier;
use App\Models\Professionnel;
use App\Models\Adresse;
use App\Models\Ville;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
            'role' => ['required', 'in:particulier,professionnel'],
            'email' => ['required', 'string', 'email', 'max:320', 'unique:utilisateur'],
            'password' => $this->passwordRules(),
            'pseudo' => ['required', 'string', 'max:50'],
            'telephone' => ['required', 'string', 'max:15'],
            
            'nomrue' => ['required', 'string'],
            'codepostal' => ['required', 'exists:ville,codepostal'],
            
            'nom' => ['required_if:role,particulier', 'nullable', 'string'],
            'prenom' => ['required_if:role,particulier', 'nullable', 'string'],
            'civilite' => ['required_if:role,particulier', 'nullable', 'in:Monsieur,Madame'],
            'date_naissance' => ['required_if:role,particulier', 'nullable', 'date'],

            'nomsociete' => ['required_if:role,professionnel', 'nullable', 'string'],
            'numsiret' => ['required_if:role,professionnel', 'nullable', 'string', 'size:14'],
            'secteuractivite' => ['required_if:role,professionnel', 'nullable', 'string'],
        ])->validate();

        
        return DB::transaction(function () use ($input) {
            
            $ville = Ville::where('codepostal', $input['codepostal'])->firstOrFail();

            $adresse = Adresse::create([
                'idville' => $ville->idville,
                'numerorue' => $input['numerorue'],
                'nomrue' => $input['nomrue'],
            ]);

            $nom = $input['role'] === 'particulier' ? $input['nom'] : $input['nomsociete'];
            
            $prenom = $input['role'] === 'particulier' ? $input['prenom'] : $input['nomsociete'];


            $user = User::create([
                'nomutilisateur' => $nom,
                'prenomutilisateur' => $prenom,
                'pseudonyme' => $input['pseudo'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'telephoneutilisateur' => $input['telephone'],
                'idadresse' => $adresse->idadresse,
                'solde' => 0,
                'idphoto' => null
            ]);

            if ($input['role'] === 'particulier') {

                $dateRef = DB::table('date')->where('date', $input['date_naissance'])->first();

                $idDate = $dateRef ? $dateRef->iddate : 1;

                Particulier::create([
                    'idutilisateur' => $user->idutilisateur, 
                    'civilite' => $input['civilite'],
                    'iddate' => $idDate,
                ]);

            } else {
                Professionnel::create([
                    'idutilisateur' => $user->idutilisateur, 
                    'numsiret' => $input['numsiret'],
                    'nomsociete' => $input['nomsociete'],
                    'secteuractivite' => $input['secteuractivite'],
                ]);
            }

            return $user;
        });
    }
}
