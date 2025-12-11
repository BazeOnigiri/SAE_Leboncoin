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
        $rules = [
            'pseudonyme' => ['required', 'string', 'min:2', 'max:50'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];

        // Conditional validation for Particulier fields
        if ($user->particulier) {
            $rules['particulier.nomutilisateur'] = ['required', 'string', 'max:50'];
            $rules['particulier.prenomutilisateur'] = ['required', 'string', 'max:50'];
        }

        Validator::make($input, $rules)->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // Update User fields
        $user->forceFill([
            'pseudonyme' => $input['pseudonyme'],
        ])->save();

        // Update Particulier fields if applicable
        if ($user->particulier && isset($input['particulier'])) {
            $user->particulier->update([
                'nomutilisateur' => $input['particulier']['nomutilisateur'],
                'prenomutilisateur' => $input['particulier']['prenomutilisateur'],
            ]);
        }
    }
}