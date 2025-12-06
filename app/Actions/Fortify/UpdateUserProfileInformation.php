<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'nomutilisateur' => ['required', 'string', 'max:50'],
            'prenomutilisateur' => ['required', 'string', 'max:50'],
            'pseudonyme' => ['required', 'string', 'min:2', 'max:50'],
            // L'email a été retiré de la validation car il n'est pas modifiable ici
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // On met à jour uniquement les champs autorisés (sans l'email)
        $user->forceFill([
            'nomutilisateur' => $input['nomutilisateur'],
            'prenomutilisateur' => $input['prenomutilisateur'],
            'pseudonyme' => $input['pseudonyme'],
        ])->save();
    }
}