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
                            required
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
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
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
                            <div class="flex gap-2">
                                <input
                                    id="locality_display"
                                    type="text"
                                    maxlength="40"
                                    value="{{ old('nomville', $user->adresse->ville->nomville ?? '') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50"
                                    readonly
                                >
                                <button
                                    type="button"
                                    id="open-city-chooser-settings"
                                    class="shrink-0 px-3 py-2 text-xs font-bold rounded-[10px] border border-gray-300 text-gray-700 hover:bg-gray-100"
                                >
                                    Choisir
                                </button>
                            </div>
                            <p class="mt-1 text-[11px] text-gray-500">
                                Si le code postal correspond à plusieurs villes, cliquez sur <strong>Choisir</strong>.
                            </p>
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

    {{-- MODAL CHOIX DE VILLE --}}
    <div
        id="city-chooser-modal-settings"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden"
    >
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Choisir une ville</h3>
            <p class="text-sm text-gray-600 mb-4">
                Plusieurs villes existent pour le code postal
                <span id="modal-cp-settings" class="font-semibold"></span>. Sélectionnez celle qui vous concerne.
            </p>

            <div id="city-radio-list-settings" class="space-y-2 max-h-60 overflow-y-auto mb-4">
                {{-- radios générés en JS --}}
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    id="city-chooser-cancel-settings"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
                >
                    Annuler
                </button>
                <button
                    type="button"
                    id="city-chooser-ok-settings"
                    class="px-4 py-2 text-sm rounded-lg bg-[#ec5a13] text-white font-bold hover:bg-[#d64d0e]"
                >
                    Valider
                </button>
            </div>
        </div>
    </div>

    <script>
function initAutocomplete() {

    const input = document.querySelector('#autocomplete');
    if (!input) return;

    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['address'],
        componentRestrictions: { country: 'fr' }
    });

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.address_components) return;

        let streetNumber = '';
        let route = '';
        let city = '';
        let postalCode = '';

        place.address_components.forEach(component => {
            const types = component.types;

            if (types.includes('street_number')) streetNumber = component.long_name;
            if (types.includes('route'))          route       = component.long_name;
            if (types.includes('locality'))       city        = component.long_name;
            if (types.includes('postal_code'))    postalCode  = component.long_name;
        });

        // Champs visibles
        document.querySelector('#street_number_display').value = streetNumber || '';
        document.querySelector('#route_display').value         = route || '';
        document.querySelector('#postal_code_display').value   = postalCode || '';
        document.querySelector('#locality_display').value      = city || '';

        // Champs hidden envoyés au backend
        document.querySelector('#numerorue').value  = streetNumber || 1;
        document.querySelector('#nomrue').value     = route;
        document.querySelector('#codepostal').value = postalCode;
        document.querySelector('#nomville').value   = city;
    });

    // ------- CHOIX DE LA VILLE (API GOUV) ---------

    const openBtn   = document.querySelector('#open-city-chooser-settings');
    const modal     = document.querySelector('#city-chooser-modal-settings');
    const okBtn     = document.querySelector('#city-chooser-ok-settings');
    const cancelBtn = document.querySelector('#city-chooser-cancel-settings');
    const listDiv   = document.querySelector('#city-radio-list-settings');
    const cpSpan    = document.querySelector('#modal-cp-settings');

    if (!openBtn) return;

    openBtn.addEventListener('click', async () => {

        const cp = document.querySelector('#codepostal').value;

        if (!cp || cp.length !== 5) {
            alert("Veuillez d'abord sélectionner une adresse pour récupérer le code postal.");
            return;
        }

        cpSpan.textContent = cp;

        listDiv.innerHTML = '<p class="text-sm text-gray-500">Chargement des villes...</p>';
        modal.classList.remove('hidden');

        try {
            const response = await fetch(
                'https://geo.api.gouv.fr/communes?codePostal=' +
                encodeURIComponent(cp) + '&fields=nom&format=json'
            );

            if (!response.ok) throw new Error('Erreur API');

            const communes = await response.json();

            if (!communes.length) {
                listDiv.innerHTML = '<p class="text-sm text-red-500">Aucune ville trouvée pour ce code postal.</p>';
                return;
            }

            listDiv.innerHTML = communes.map((commune, i) => {
                return `
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="radio"
                            name="city-settings-choice"
                            value="${commune.nom}"
                            class="text-orange-600 focus:ring-orange-600"
                            ${i === 0 ? 'checked' : ''}
                        >
                        <span>${commune.nom}</span>
                    </label>
                `;
            }).join('');

        } catch (e) {
            console.error(e);
            listDiv.innerHTML = '<p class="text-sm text-red-500">Erreur lors de la récupération des villes.</p>';
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
    }

    cancelBtn.addEventListener('click', closeModal);

    okBtn.addEventListener('click', () => {
        const selected = document.querySelector('input[name="city-settings-choice"]:checked');

        if (!selected) {
            alert("Veuillez sélectionner une ville.");
            return;
        }

        const cityName = selected.value;

        // Mise à jour des champs
        document.querySelector('#locality_display').value = cityName;
        document.querySelector('#nomville').value         = cityName;

        closeModal();
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
