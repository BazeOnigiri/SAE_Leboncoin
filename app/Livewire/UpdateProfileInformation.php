<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as JetstreamUpdateProfileInformationForm;

class UpdateProfileInformation extends JetstreamUpdateProfileInformationForm
{
    public function mount()
    {
        $user = Auth::user();

        $this->state = $user->withoutRelations()->toArray();

        if ($user->particulier) {
            $this->state['particulier'] = $user->particulier->toArray();
        }
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $updater->update(Auth::user(), $this->photo
            ? array_merge($this->state, ['photo' => $this->photo])
            : $this->state);

        return redirect()->route('dashboard');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
