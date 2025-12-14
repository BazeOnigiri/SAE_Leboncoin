<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Paramètres</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Paramètres</h1>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8" x-data="{
                // Variables pour la recherche d'adresse
                query: '',
                suggestions: [],
                showSuggestions: false,

                // Variables pour la modale de choix de ville
                showCityModal: false,
                citiesByCp: [],
                modalCp: '',
                selectedCityRadio: '',

                // Recherche d'adresse via API Gouv
                searchAddress() {
                    if (this.query.length < 3) {
                        this.suggestions = [];
                        return;
                    }
                    fetch(`https://api-adresse.data.gouv.fr/search/?q=${this.query}&limit=5&autocomplete=1`)
                        .then(response => response.json())
                        .then(data => {
                            this.suggestions = data.features;
                            this.showSuggestions = true;
                        });
                },

                // Sélection d'une adresse dans la liste
                selectAddress(feature) {
                    const props = feature.properties;
                    
                    // Remplissage des champs visibles (readonly)
                    document.getElementById('street_number_display').value = props.housenumber || '';
                    document.getElementById('route_display').value = props.street || props.name;
                    document.getElementById('postal_code_display').value = props.postcode;
                    document.getElementById('locality_display').value = props.city;

                    // Remplissage des champs cachés (envoyés au serveur)
                    document.getElementById('numerorue').value = props.housenumber || 1;
                    document.getElementById('nomrue').value = props.street || props.name;
                    document.getElementById('codepostal').value = props.postcode;
                    document.getElementById('nomville').value = props.city;

                    this.query = ''; 
                    this.showSuggestions = false;
                },

                // Ouverture de la modale pour choisir la ville exacte
                async openCityChooser() {
                    const cp = document.getElementById('postal_code_display').value;
                    const currentCity = document.getElementById('locality_display').value;

                    if (!cp || cp.length !== 5) {
                        alert('Veuillez d\'abord rechercher une adresse pour avoir un Code Postal.');
                        return;
                    }

                    this.modalCp = cp;
                    this.citiesByCp = [];
                    this.showCityModal = true;

                    try {
                        const response = await fetch(`https://geo.api.gouv.fr/communes?codePostal=${cp}&fields=nom&format=json`);
                        if (!response.ok) throw new Error('Erreur API');
                        this.citiesByCp = await response.json();

                        if (this.citiesByCp.length > 0) {
                            const foundCity = this.citiesByCp.find(city => city.nom.toUpperCase() === currentCity.toUpperCase());
                            
                            if (currentCity && foundCity) {
                                this.selectedCityRadio = foundCity.nom;
                            } else {
                                this.selectedCityRadio = this.citiesByCp[0].nom;
                            }
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Impossible de charger les villes.');
                        this.showCityModal = false;
                    }
                },

                // Validation du choix dans la modale
                validateCityChoice() {
                    if (!this.selectedCityRadio) return;
                    
                    document.getElementById('locality_display').value = this.selectedCityRadio;
                    document.getElementById('nomville').value = this.selectedCityRadio;
                    
                    this.showCityModal = false;
                }
            }">

                <h2 class="text-xl font-bold mb-6">Informations</h2>
                
                <form method="POST" action="{{ route('user.settings.update') }}">
                    @csrf
                    
                    {{-- ================================================= --}}
                    {{-- CAS PARTICULIER --}}
                    {{-- ================================================= --}}
                    @if($user->particulier)
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Civilité</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition {{ optional($user->particulier)->civilite == 'Madame' ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }}">
                                    <input type="radio" name="civilite" value="Madame" class="text-orange-600 focus:ring-orange-600" {{ optional($user->particulier)->civilite == 'Madame' ? 'checked' : '' }}>
                                    <span>Madame</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition {{ optional($user->particulier)->civilite == 'Monsieur' ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }}">
                                    <input type="radio" name="civilite" value="Monsieur" class="text-orange-600 focus:ring-orange-600" {{ optional($user->particulier)->civilite == 'Monsieur' ? 'checked' : '' }}>
                                    <span>Monsieur</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition {{ optional($user->particulier)->civilite == 'Non spécifié' ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }}">
                                    <input type="radio" name="civilite" value="Non spécifié" class="text-orange-600 focus:ring-orange-600" {{ optional($user->particulier)->civilite == 'Non spécifié' ? 'checked' : '' }}>
                                    <span>Non spécifié</span>
                                </label>
                            </div>
                            @error('civilite') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
                                <input type="text" name="nom" maxlength="50" value="{{ old('nom', $user->particulier->nomutilisateur) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
                                <input type="text" name="prenom" maxlength="50" value="{{ old('prenom', $user->particulier->prenomutilisateur) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('prenom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" name="date_naissance" required max="{{ now()->subYears(18)->toDateString() }}" value="{{ old('date_naissance', optional(optional($user->particulier)->dateNaissance)->date) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                            @error('date_naissance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                    {{-- ================================================= --}}
                    {{-- CAS PROFESSIONNEL --}}
                    {{-- ================================================= --}}
                    @elseif($user->professionnels)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nom de la société</label>
                                <input type="text" name="nomsociete" maxlength="30" value="{{ old('nomsociete', $user->professionnels->nomsociete) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('nomsociete') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">N° SIRET</label>
                                <input type="text" name="numsiret" maxlength="14" pattern="[0-9]{14}" inputmode="numeric" value="{{ old('numsiret', $user->professionnels->numsiret) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="14 chiffres">
                                @error('numsiret') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Secteur d'activité</label>
                            <select name="secteuractivite" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                <option value="">Sélectionnez un secteur</option>
                                    @foreach($secteurs as $secteur)
                                        <option value="{{ $secteur }}" {{ old('secteuractivite', $user->professionnels->secteuractivite) == $secteur ? 'selected' : '' }}>
                                            {{ $secteur }}
                                        </option>
                                    @endforeach
                            </select>
                            @error('secteuractivite') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    {{-- CHAMPS COMMUNS --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone</label>
                        <input type="tel" name="telephone" maxlength="10" pattern="0[1-9][0-9]{8}" inputmode="numeric" placeholder="Ex : 0612345678" value="{{ old('telephone', $user->telephoneutilisateur) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        @error('telephone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <hr class="my-8 border-gray-200">

                    {{-- ================================================= --}}
                    {{-- COORDONNÉES (VERSION API GOUV) --}}
                    {{-- ================================================= --}}
                    <h2 class="text-xl font-bold mb-6">Coordonnées</h2>

                    <div class="mb-6 relative">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rechercher votre adresse</label>
                        <input 
                            id="autocomplete" 
                            type="text" 
                            x-model="query"
                            @input.debounce.300ms="searchAddress()"
                            placeholder="Tapez votre nouvelle adresse..." 
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" 
                            autocomplete="off"
                        >
                        <p class="text-xs text-gray-500 mt-1">Le champ ci-dessus remplit automatiquement les champs N°, Voie, Code postal et Ville.</p>

                        {{-- Liste de suggestions --}}
                        <div x-show="showSuggestions && suggestions.length > 0" @click.away="showSuggestions = false" class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                            <template x-for="feature in suggestions" :key="feature.properties.id">
                                <div @click="selectAddress(feature)" class="px-4 py-2 hover:bg-orange-50 cursor-pointer border-b border-gray-100 last:border-0 text-sm text-gray-700">
                                    <span class="font-bold" x-text="feature.properties.label"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">N°</label>
                            <input id="street_number_display" type="text" value="{{ old('numerorue', $user->adresse->numerorue ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50" readonly>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Voie</label>
                            <input id="route_display" type="text" value="{{ old('nomrue', $user->adresse->nomrue ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-2">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Code postal</label>
                            <input id="postal_code_display" type="text" value="{{ old('codepostal', $user->adresse->ville->codepostal ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50" readonly>
                            @error('codepostal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ville</label>
                            <div class="flex gap-2">
                                <input id="locality_display" type="text" value="{{ old('nomville', $user->adresse->ville->nomville ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50" readonly>
                                <button type="button" @click="openCityChooser()" class="shrink-0 px-3 py-2 text-xs font-bold rounded-[10px] border border-gray-300 text-gray-700 hover:bg-gray-100">
                                    Choisir
                                </button>
                            </div>
                            <p class="mt-1 text-[11px] text-gray-500">Si le code postal correspond à plusieurs villes, cliquez sur <strong>Choisir</strong>.</p>
                            @error('nomville') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- CHAMPS CACHÉS pour le backend --}}
                    <input type="hidden" id="numerorue" name="numerorue" value="{{ old('numerorue', $user->adresse->numerorue ?? '') }}">
                    <input type="hidden" id="nomrue" name="nomrue" value="{{ old('nomrue', $user->adresse->nomrue ?? '') }}">
                    <input type="hidden" id="codepostal" name="codepostal" value="{{ old('codepostal', $user->adresse->ville->codepostal ?? '') }}">
                    <input type="hidden" id="nomville" name="nomville" value="{{ old('nomville', $user->adresse->ville->nomville ?? '') }}">

                    <hr class="my-8 border-gray-200">

                    <h2 class="text-xl font-bold mb-6">Informations de connexion</h2>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Adresse email</label>
                        <input type="email" name="email" maxlength="320" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-orange-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-orange-700 transition shadow-sm">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>

                <div x-show="showCityModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                    <div @click.away="showCityModal = false" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Choisir une ville</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Plusieurs villes existent pour le code postal <span class="font-semibold" x-text="modalCp"></span>. Sélectionnez la bonne :
                        </p>

                        <div x-show="citiesByCp.length === 0" class="text-sm text-gray-500 py-4 text-center">Chargement...</div>

                        <div class="space-y-2 max-h-60 overflow-y-auto mb-4">
                            <template x-for="(city, index) in citiesByCp" :key="index">
                                <label class="flex items-center gap-2 text-sm cursor-pointer p-2 hover:bg-gray-50 rounded">
                                    <input
                                        type="radio"
                                        name="city_settings_choice"
                                        :value="city.nom"
                                        x-model="selectedCityRadio"
                                        class="text-[#ec5a13] focus:ring-[#ec5a13]"
                                    >
                                    <span x-text="city.nom"></span>
                                </label>
                            </template>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showCityModal = false" class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Annuler</button>
                            <button type="button" @click="validateCityChoice()" class="px-4 py-2 text-sm rounded-lg bg-[#ec5a13] text-white font-bold hover:bg-[#d64d0e]">Valider</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endsection
</x-app-layout>