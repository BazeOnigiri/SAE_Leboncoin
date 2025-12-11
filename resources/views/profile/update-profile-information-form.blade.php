<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Informations du profil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Mettez à jour les informations de profil de votre compte.') }}
    </x-slot>

    <x-slot name="form">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Sélectionner une nouvelle photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Supprimer la photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        @if (!$this->user->professionnels)
        <div class="col-span-6 sm:col-span-4">
            <x-label for="nom" value="{{ __('Nom') }}" />
            <x-input id="nom" type="text" class="mt-1 block w-full" wire:model="state.particulier.nomutilisateur" required autocomplete="family-name" />
            <x-input-error for="particulier.nomutilisateur" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="prenom" value="{{ __('Prénom') }}" />
            <x-input id="prenom" type="text" class="mt-1 block w-full" wire:model="state.particulier.prenomutilisateur" required autocomplete="given-name" />
            <x-input-error for="particulier.prenomutilisateur" class="mt-2" />
        </div>
        @endif

        <div class="col-span-6 sm:col-span-4">
            <x-label for="pseudonyme" value="{{ __('Pseudonyme') }}" />
            <x-input id="pseudonyme" type="text" class="mt-1 block w-full" wire:model="state.pseudonyme" required autocomplete="nickname" />
            <x-input-error for="pseudonyme" class="mt-2" />
        </div>

        </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Enregistré.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Enregistrer') }}
        </x-button>
    </x-slot>
</x-form-section>