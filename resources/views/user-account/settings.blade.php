<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Paramètres</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Paramètres</h1>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold mb-6">Informations personnelles</h2>
                
                <form method="POST" action="{{ route('user.settings.update') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Civilité</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="civilite"
                                    value="Madame"
                                    class="text-orange-600 focus:ring-orange-600"
                                    {{ optional($user->particulier)->civilite == 'Madame' ? 'checked' : '' }}
                                >
                                <span>Madame</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="civilite"
                                    value="Monsieur"
                                    class="text-orange-600 focus:ring-orange-600"
                                    {{ optional($user->particulier)->civilite == 'Monsieur' ? 'checked' : '' }}
                                >
                                <span>Monsieur</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="civilite"
                                    value="Non spécifié"
                                    class="text-orange-600 focus:ring-orange-600"
                                    {{ optional($user->particulier)->civilite == 'Non spécifié' ? 'checked' : '' }}
                                >
                                <span>Non spécifié</span>
                            </label>
                        </div>
                        @error('civilite') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
                            <input
                                type="text"
                                name="nom"
                                maxlength="50"
                                value="{{ old('nom', $user->nomutilisateur) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            >
                            @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
                            <input
                                type="text"
                                name="prenom"
                                maxlength="50"
                                value="{{ old('prenom', $user->prenomutilisateur) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            >
                            @error('prenom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date de naissance</label>
                        <input
                            type="date"
                            name="date_naissance"
                            max="{{ now()->subYears(18)->toDateString() }}"
                            value="{{ old('date_naissance', optional(optional($user->particulier)->dateNaissance)->date) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                        >
                        @error('date_naissance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone</label>
                        <input
                            type="tel"
                            name="telephone"
                            maxlength="10"
                            pattern="0[1-9][0-9]{8}"
                            inputmode="numeric"
                            placeholder="Ex : 0612345678"
                            value="{{ old('telephone', $user->telephoneutilisateur) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                        >
                        @error('telephone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <hr class="my-8 border-gray-200">

                    <h2 class="text-xl font-bold mb-6">Coordonnées</h2>

                    {{-- Champ de recherche d’adresse via Google --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Rechercher votre adresse
                        </label>
                        <input
                            id="autocomplete"
                            type="text"
                            placeholder="Commencez à taper votre adresse..."
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            autocomplete="off"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Le champ ci-dessus remplit automatiquement les champs N°, Voie, Code postal et Ville.
                        </p>
                    </div>

                    {{-- Champs visibles (lecture seule) --}}
                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">N°</label>
                            <input
                                id="street_number_display"
                                type="number"
                                min="1"
                                max="99999"
                                value="{{ old('numerorue', $user->adresse->numerorue ?? '') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50"
                                readonly
                            >
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Voie</label>
                            <input
                                id="route_display"
                                type="text"
                                maxlength="39"
                                value="{{ old('nomrue', $user->adresse->nomrue ?? '') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50"
                                placeholder="Nom de la rue"
                                readonly
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-2">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Code postal</label>
                            <input
                                id="postal_code_display"
                                type="text"
                                maxlength="5"
                                pattern="[0-9]{5}"
                                inputmode="numeric"
                                value="{{ old('codepostal', $user->adresse->ville->codepostal ?? '') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50"
                                readonly
                            >
                            @error('codepostal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ville</label>
                            <input
                                id="locality_display"
                                type="text"
                                maxlength="40"
                                value="{{ old('nomville', $user->adresse->ville->nomville ?? '') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50"
                                readonly
                            >
                            @error('nomville') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- AJOUT : Section Email --}}
                    <hr class="my-8 border-gray-200">

                    <h2 class="text-xl font-bold mb-6">Informations de connexion</h2>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Adresse email</label>
                        <input
                            type="email"
                            name="email"
                            maxlength="320"
                            value="{{ old('email', $user->email) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                        >
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Champs cachés réellement envoyés au backend --}}
                    <input
                        type="hidden"
                        id="numerorue"
                        name="numerorue"
                        value="{{ old('numerorue',  $user->adresse->numerorue ?? '') }}"
                    >
                    <input
                        type="hidden"
                        id="nomrue"
                        name="nomrue"
                        value="{{ old('nomrue', $user->adresse->nomrue ?? '') }}"
                    >
                    <input
                        type="hidden"
                        id="codepostal"
                        name="codepostal"
                        value="{{ old('codepostal', $user->adresse->ville->codepostal ?? '') }}"
                    >
                    <input
                        type="hidden"
                        id="nomville"
                        name="nomville"
                        value="{{ old('nomville', $user->adresse->ville->nomville ?? '') }}"
                    >

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-orange-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-orange-700 transition shadow-sm">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function initAutocomplete() {
            const input = document.getElementById('autocomplete');
            if (!input) return;

            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['address'],
                componentRestrictions: { country: 'fr' }
            });

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                if (!place.address_components) return;

                let streetNumber = '';
                let route = '';
                let city = '';
                let postalCode = '';

                place.address_components.forEach(function (component) {
                    const types = component.types;

                    if (types.includes('street_number')) {
                        streetNumber = component.long_name;
                    }
                    if (types.includes('route')) {
                        route = component.long_name;
                    }
                    if (types.includes('locality')) {
                        city = component.long_name;
                    }
                    if (types.includes('postal_code')) {
                        postalCode = component.long_name;
                    }
                });

                // champs visibles
                const streetNumberDisplay = document.getElementById('street_number_display');
                const routeDisplay        = document.getElementById('route_display');
                const postalDisplay       = document.getElementById('postal_code_display');
                const cityDisplay         = document.getElementById('locality_display');

                if (streetNumberDisplay) streetNumberDisplay.value = streetNumber || '';
                if (routeDisplay)        routeDisplay.value        = route || '';
                if (postalDisplay)       postalDisplay.value       = postalCode || '';
                if (cityDisplay)         cityDisplay.value         = city || '';

                // champs cachés envoyés au backend
                const numHidden   = document.getElementById('numerorue');
                const routeHidden = document.getElementById('nomrue');
                const cpHidden    = document.getElementById('codepostal');
                const villeHidden = document.getElementById('nomville');

                if (numHidden)   numHidden.value   = streetNumber || 1;
                if (routeHidden) routeHidden.value = route || '';
                if (cpHidden)    cpHidden.value    = postalCode || '';
                if (villeHidden) villeHidden.value = city || '';
            });
        }

        window.initAutocomplete = initAutocomplete;
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete"
        async defer>
    </script>
    @endsection
</x-app-layout>