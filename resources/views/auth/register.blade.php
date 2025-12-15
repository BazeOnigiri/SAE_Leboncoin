<x-guest-layout>
    <div class="absolute top-6 left-6 z-10">
        <a href="{{ route('auth.check') }}" class="flex items-center gap-2 text-gray-600 hover:text-orange-600 font-medium transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Retour à l'accueil</span>
        </a>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 py-12">
        <x-authentication-card>
            <x-slot name="logo">
                <x-authentication-card-logo />
            </x-slot>

            <x-validation-errors class="mb-4" />

            <div x-data="{
                step: @js($errors->any() ? 2 : 1),
                role: @js(old('role', 'particulier')),
                
                // --- Variables Recherche Adresse ---
                query: @js(old('ville') ? (old('numerorue') ? old('numerorue') . ' ' : '') . old('nomrue') . ' ' . old('codepostal') . ' ' . old('ville') : ''),

                suggestions: [],
                showSuggestions: false,
                
                addressSelected: @js(old('ville') ? true : false),

                // --- Variables Choix Ville ---
                showCityModal: false,
                citiesByCp: [], 
                modalCp: '', 
                selectedCityRadio: '', 

                // Fonction : Autocomplétion Adresse (API Gouv)
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

                // Fonction : Sélectionner une adresse depuis la liste
                selectAddress(feature) {
                    // Remplissage visuel
                    document.querySelector('#autocomplete').value = feature.properties.label;
                    document.querySelector('#street_number_display').value = feature.properties.housenumber || '';
                    document.querySelector('#route_display').value = feature.properties.street || feature.properties.name; 
                    document.querySelector('#postal_code_display').value = feature.properties.postcode;
                    document.querySelector('#locality_display').value = feature.properties.city;

                    // Remplissage backend (Champs cachés)
                    document.querySelector('#numerorue').value = feature.properties.housenumber || 1;
                    document.querySelector('#nomrue').value = feature.properties.street || feature.properties.name;
                    document.querySelector('#codepostal').value = feature.properties.postcode;
                    document.querySelector('#ville').value = feature.properties.city;

                    this.query = feature.properties.label;
                    this.showSuggestions = false;
                    this.addressSelected = true;
                },

                // Fonction : Ouvrir la modale 'Choisir Ville'
                async openCityChooser() {
                    const cp = document.querySelector('#codepostal').value;
                    const currentCity = document.querySelector('#ville').value; 

                    if (!cp || cp.length !== 5) {
                        alert(`Veuillez d'abord sélectionner une adresse.`);
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
                            // On vérifie si la ville actuelle est dans la liste (insensible à la casse)
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

                // Fonction : Valider le choix dans la modale
                validateCityChoice() {
                    if (!this.selectedCityRadio) return;
                    document.querySelector('#locality_display').value = this.selectedCityRadio;
                    document.querySelector('#ville').value = this.selectedCityRadio;
                    this.showCityModal = false;
                },

                selectRole(type) {
                    this.role = type;
                    this.step = 2;
                }
            }">
                
                <div x-show="step === 1" x-transition>
                    <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Créez un compte</h2>
                    <p class="text-center text-gray-500 text-sm mb-8">Choisissez votre profil pour commencer</p>

                    <div class="space-y-4">
                        <div @click="selectRole('particulier')" class="cursor-pointer group border rounded-lg p-4 hover:border-orange-500 hover:bg-orange-50 transition flex items-center">
                            <div class="h-6 w-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 group-hover:border-orange-500">
                                <div class="h-3 w-3 rounded-full bg-orange-500 opacity-0 group-hover:opacity-100 transition"></div>
                            </div>
                            <div><span class="block font-bold text-gray-800">Pour vous <span class="text-orange-600">*</span></span><span class="text-sm text-gray-500">Vous êtes un particulier</span></div>
                        </div>

                        <div @click="selectRole('professionnel')" class="cursor-pointer group border rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition flex items-center">
                            <div class="h-6 w-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 group-hover:border-blue-500">
                                <div class="h-3 w-3 rounded-full bg-blue-500 opacity-0 group-hover:opacity-100 transition"></div>
                            </div>
                            <div><span class="block font-bold text-gray-800">Pour votre entreprise</span><span class="text-sm text-gray-500">Vous êtes un professionnel (SIRET requis)</span></div>
                        </div>
                    </div>
                    <div class="mt-8 text-center"><p class="text-sm text-gray-600">Déjà un compte ? <a href="{{ route('auth.check') }}" class="font-bold text-[#ec5a13] hover:underline">Me connecter</a></p></div>
                </div>

                <form x-show="step === 2" x-cloak method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="role" x-model="role">
                    <input type="hidden" name="email" value="{{ request('email') ?? old('email') }}">

                    <div class="mb-6 pb-4 border-b border-gray-100">
                        <button 
                            type="button" 
                            @click="step = 1" 
                            class="flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-[#ec5a13] mb-2 transition"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Changer de type de compte</span>
                        </button>

                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">
                                <span x-show="role === 'particulier'">Compte Particulier</span>
                                <span x-show="role === 'professionnel'">Compte Professionnel</span>
                            </h3>
                            <span 
                                class="px-3 py-1 rounded-full text-xs font-bold"
                                :class="role === 'particulier' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700'"
                                x-text="role === 'particulier' ? 'Perso' : 'Pro'"
                            ></span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">E-mail</label>
                        <input type="email" class="w-full border-gray-300 rounded-[10px] py-3 px-4 bg-gray-100 text-gray-500 cursor-not-allowed" disabled value="{{ request('email') ?? old('email') }}">
                    </div>

                    {{-- CHAMPS PARTICULIER --}}
                    <div x-show="role === 'particulier'" class="space-y-5">
                        <div class="flex gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="civilite" value="Monsieur" class="form-radio text-gray-900 focus:ring-gray-900" {{ old('civilite', 'Monsieur') == 'Monsieur' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Monsieur</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="civilite" value="Madame" class="form-radio text-gray-900 focus:ring-gray-900" {{ old('civilite') == 'Madame' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Madame</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="civilite" value="Non spécifié" class="form-radio text-gray-900 focus:ring-gray-900" {{ old('civilite') == 'Non spécifié' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Non spécifié</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Nom *</label>
                                <input type="text" name="nom" value="{{ old('nom') }}" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0">
                                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Prénom *</label>
                                <input type="text" name="prenom" value="{{ old('prenom') }}" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0">
                                @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Date de naissance *</label>
                            <input type="date" name="date_naissance" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 bg-white" max="{{ now()->subYears(18)->toDateString() }}" value="{{ old('date_naissance') }}">
                            @error('date_naissance') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- CHAMPS PROFESSIONNEL --}}
                    <div x-show="role === 'professionnel'" class="space-y-5">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Numéro SIRET *</label>
                            <input type="text" name="numsiret" maxlength="14" placeholder="14 chiffres" value="{{ old('numsiret') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 14)" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0">
                            @error('numsiret') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Nom de la société *</label>
                            <input type="text" name="nomsociete" value="{{ old('nomsociete') }}" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 uppercase">
                            @error('nomsociete') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Secteur d'activité *</label>
                            <select name="secteuractivite" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 bg-white">
                                <option value="">Sélectionnez un secteur</option>
                                @foreach(\App\Actions\Fortify\CreateNewUser::SECTEURS as $secteur)
                                <option value="{{ $secteur }}" {{ old('secteuractivite') == $secteur ? 'selected' : '' }}>
                                    {{ $secteur }}
                                </option>
                                @endforeach
                            </select>
                            @error('secteuractivite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="border-t pt-5">
                        <h4 class="font-bold text-gray-900 mb-4 text-lg">Adresse</h4>

                        <div class="mb-4 relative">
                            <label class="block font-bold text-sm text-gray-700 mb-1">Rechercher votre adresse *</label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-4 top-4 text-gray-400"></i>
                                <input
                                    id="autocomplete"
                                    type="text"
                                    x-model="query"
                                    @input.debounce.300ms="searchAddress()"
                                    placeholder="Tapez votre adresse..."
                                    class="w-full border-gray-300 rounded-[10px] py-3 pl-10 px-4 focus:border-[#ec5a13] focus:ring-0"
                                    autocomplete="off">
                            </div>

                            <div x-show="showSuggestions && suggestions.length > 0" @click.away="showSuggestions = false" class="absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                                <template x-for="feature in suggestions" :key="feature.properties.id">
                                    <div @click="selectAddress(feature)" class="px-4 py-2 hover:bg-orange-50 cursor-pointer border-b border-gray-100 last:border-0 text-sm text-gray-700">
                                        <span class="font-bold" x-text="feature.properties.label"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            <div class="col-span-1">
                                <label class="block font-bold text-xs text-gray-500 mb-1">N°</label>
                                <input type="text" id="street_number_display" class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600" disabled value="{{ old('numerorue') }}">
                                <input type="hidden" name="numerorue" id="numerorue" value="{{ old('numerorue') }}">
                            </div>
                            <div class="col-span-3">
                                <label class="block font-bold text-xs text-gray-500 mb-1">Rue</label>
                                <input type="text" id="route_display" class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600" disabled value="{{ old('nomrue') }}">
                                <input type="hidden" name="nomrue" id="nomrue" value="{{ old('nomrue') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="col-span-1">
                                <label class="block font-bold text-xs text-gray-500 mb-1">CP</label>
                                <input type="text" id="postal_code_display" class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600" disabled value="{{ old('codepostal') }}">
                                <input type="hidden" name="codepostal" id="codepostal" value="{{ old('codepostal') }}">
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block font-bold text-xs text-gray-500 mb-1">Ville</label>
                                <div class="flex gap-2">
                                    <input type="text" id="locality_display" class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600" disabled value="{{ old('ville') }}">
                                    <button type="button" @click="openCityChooser()" class="shrink-0 px-3 py-2 text-xs font-bold rounded-[10px] border border-gray-300 text-gray-700 hover:bg-gray-100">Choisir</button>
                                </div>
                                <input type="hidden" name="ville" id="ville" value="{{ old('ville') }}">
                                <p class="mt-1 text-[11px] text-gray-500">
                                    Si le code postal correspond à plusieurs villes, cliquez sur <strong>Choisir</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-5">
                        <h4 class="font-bold text-gray-900 mb-4 text-lg">Identifiants</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Pseudonyme *</label>
                                <input type="text" name="pseudo" value="{{ old('pseudo') }}" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0" required>
                                @error('pseudo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Téléphone *</label>
                                <input type="tel" name="telephone" value="{{ old('telephone') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0" required>
                                @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ showPass: false, showConfirm: false }">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Mot de passe *</label>
                                <div class="relative">
                                    <input id="password" name="password" :type="showPass ? 'text' : 'password'" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 text-gray-900 pr-10" required>
                                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer" tabindex="-1">
                                        <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Confirmer *</label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 text-gray-900 pr-10" required>
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer" tabindex="-1">
                                        <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-2 text-xs text-gray-500">
                            Le mot de passe doit contenir au moins 12 caractères avec une majuscule, un caractère spécial et un chiffre.
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" name="terms" required class="mt-1 rounded border-gray-300 text-[#ec5a13] focus:ring-[#ec5a13]">
                            <span class="text-sm text-gray-600">
                                J'accepte les <a href="#" class="underline hover:text-[#ec5a13]">Conditions Générales d'Utilisation</a> et la <a href="#" class="underline hover:text-[#ec5a13]">Politique de Confidentialité</a>.
                            </span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="!addressSelected && step === 2" class="w-full bg-[#ec5a13] hover:bg-[#d64d0e] disabled:bg-gray-400 text-white font-bold py-3 px-4 rounded-[18px] transition duration-200 text-center shadow-md">Créer mon compte</button>
                    </div>

                    <div
                        x-show="showCityModal"
                        x-cloak
                        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
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
                                            name="city_choice_radio"
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
                </form>
            </div>
        </x-authentication-card>
    </div>
</x-guest-layout>