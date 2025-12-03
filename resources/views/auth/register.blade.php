<x-guest-layout>
    <div class="absolute top-6 left-6 z-10">
        <a href="{{ route('auth.check') }}" class="flex items-center gap-2 text-gray-600 hover:text-orange-600 font-medium transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 py-12">
        <x-authentication-card>
            <x-slot name="logo">
                <x-authentication-card-logo />
            </x-slot>

            <x-validation-errors class="mb-4" />

            <div
                x-data="{
                    step: @js($errors->any() ? 2 : 1),
                    role: @js(old('role', 'particulier')),
                    selectRole(type) {
                        this.role = type;
                        this.step = 2;
                    }
                }"
            >

            <div x-show="step === 1" x-transition>
                    <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Créez un compte</h2>
                    <p class="text-center text-gray-500 text-sm mb-8">Choisissez votre profil pour commencer</p>

                    <div class="space-y-4">
                        <div @click="selectRole('particulier')" class="cursor-pointer group border rounded-lg p-4 hover:border-orange-500 hover:bg-orange-50 transition flex items-center">
                            <div class="h-6 w-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 group-hover:border-orange-500">
                                <div class="h-3 w-3 rounded-full bg-orange-500 opacity-0 group-hover:opacity-100 transition"></div>
                            </div>
                            <div>
                                <span class="block font-bold text-gray-800">Pour vous <span class="text-orange-600">*</span></span>
                                <span class="text-sm text-gray-500">Vous êtes un particulier</span>
                            </div>
                        </div>

                        <div @click="selectRole('professionnel')" class="cursor-pointer group border rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition flex items-center">
                            <div class="h-6 w-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 group-hover:border-blue-500">
                                <div class="h-3 w-3 rounded-full bg-blue-500 opacity-0 group-hover:opacity-100 transition"></div>
                            </div>
                            <div>
                                <span class="block font-bold text-gray-800">Pour votre entreprise</span>
                                <span class="text-sm text-gray-500">Vous êtes un professionnel (SIRET requis)</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Déjà un compte ?
                            <a href="{{ route('auth.check') }}" class="font-bold text-[#ec5a13] hover:underline">
                                Me connecter
                            </a>
                        </p>
                    </div>
                </div>

                <form x-show="step === 2" method="POST" action="{{ route('register') }}" x-cloak class="space-y-5">
                    @csrf

                    <input type="hidden" name="role" x-model="role">
                    <input type="hidden" name="email" value="{{ request('email') ?? old('email') }}">

                    <div class="flex items-center mb-6">
                        <button type="button" @click="step = 1" class="text-gray-500 hover:text-[#ec5a13] mr-4">
                            <i class="fa-solid fa-arrow-left text-xl"></i>
                        </button>
                        <h3 class="text-xl font-bold text-gray-900" x-text="role === 'particulier' ? 'Compte Particulier' : 'Compte Professionnel'"></h3>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">E-mail</label>
                        <input
                            type="email"
                            class="w-full border-gray-300 rounded-[10px] py-3 px-4 bg-gray-100 text-gray-500 cursor-not-allowed"
                            disabled
                            value="{{ request('email') ?? old('email') }}"
                        >
                    </div>

                    <div x-show="role === 'particulier'" class="space-y-5">
                        <div class="flex gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input
                                    type="radio"
                                    name="civilite"
                                    value="Monsieur"
                                    class="form-radio text-gray-900 focus:ring-gray-900"
                                    @checked(old('civilite', 'Monsieur') === 'Monsieur')
                                >
                                <span class="ml-2 text-gray-700">Monsieur</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input
                                    type="radio"
                                    name="civilite"
                                    value="Madame"
                                    class="form-radio text-gray-900 focus:ring-gray-900"
                                    @checked(old('civilite') === 'Madame')
                                >
                                <span class="ml-2 text-gray-700">Madame</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Nom *</label>
                                <input
                                    type="text"
                                    name="nom"
                                    value="{{ old('nom') }}"
                                    class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0"
                                >
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Prénom *</label>
                                <input
                                    type="text"
                                    name="prenom"
                                    value="{{ old('prenom') }}"
                                    class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Date de naissance *</label>
                            <input
                                type="date"
                                id="date_naissance"
                                name="date_naissance"
                                class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 bg-white"
                                max="{{ now()->subYears(18)->toDateString() }}"
                                value="{{ old('date_naissance') }}"
                                required
                            >
                        </div>
                    </div>

                    <div x-show="role === 'professionnel'" class="space-y-5">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <label class="block font-bold text-sm text-blue-800 mb-1">Numéro SIRET *</label>
                            <input
                                type="text"
                                name="numsiret"
                                maxlength="14"
                                placeholder="14 chiffres"
                                value="{{ old('numsiret') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 14)"
                                class="w-full border-blue-200 rounded-[10px] py-3 px-4 focus:border-blue-500 focus:ring-0"
                            >
                        </div>

                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Nom de la société *</label>
                            <input
                                type="text"
                                name="nomsociete"
                                value="{{ old('nomsociete') }}"
                                class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 uppercase"
                            >
                        </div>

                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Secteur d'activité *</label>
                            <select
                                name="secteuractivite"
                                class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 bg-white"
                            >
                                <option value="Vacances"   @selected(old('secteuractivite') === 'Vacances')>Vacances</option>
                                <option value="Immobilier" @selected(old('secteuractivite') === 'Immobilier')>Immobilier</option>
                                <option value="Services"   @selected(old('secteuractivite') === 'Services')>Services</option>
                                <option value="Autre"      @selected(old('secteuractivite') === 'Autre')>Autre</option>
                            </select>
                        </div>
                    </div>

                    @php
                        $hasAddressOld = old('numerorue') || old('nomrue') || old('codepostal') || old('ville');
                        $addressOld = trim(
                            (old('numerorue').' '.old('nomrue').' '.old('codepostal').' '.old('ville'))
                        );
                    @endphp

                    <div class="border-t pt-5">
                        <h4 class="font-bold text-gray-900 mb-4 text-lg">Adresse</h4>

                        <div class="mb-4">
                            <label class="block font-bold text-sm text-gray-700 mb-1">Rechercher votre adresse *</label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-4 top-4 text-gray-400"></i>
                                <input
                                    id="autocomplete"
                                    type="text"
                                    placeholder="Commencez à taper votre adresse..."
                                    class="w-full border-gray-300 rounded-[10px] py-3 pl-10 px-4 focus:border-[#ec5a13] focus:ring-0"
                                    autocomplete="off"
                                    value="{{ $hasAddressOld ? $addressOld : '' }}"
                                    required
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            <div class="col-span-1">
                                <label class="block font-bold text-xs text-gray-500 mb-1">N°</label>
                                <input
                                    type="text"
                                    id="street_number_display"
                                    value="{{ old('numerorue') }}"
                                    class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600"
                                    disabled
                                >
                                <input type="hidden" id="numerorue" name="numerorue" value="{{ old('numerorue') }}">
                            </div>

                            <div class="col-span-3">
                                <label class="block font-bold text-xs text-gray-500 mb-1">Rue</label>
                                <input
                                    type="text"
                                    id="route_display"
                                    value="{{ old('nomrue') }}"
                                    class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600"
                                    disabled
                                >
                                <input type="hidden" id="nomrue" name="nomrue" value="{{ old('nomrue') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="col-span-1">
                                <label class="block font-bold text-xs text-gray-500 mb-1">CP</label>
                                <input
                                    type="text"
                                    id="postal_code_display"
                                    value="{{ old('codepostal') }}"
                                    class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600"
                                    disabled
                                >
                                <input type="hidden" id="codepostal" name="codepostal" value="{{ old('codepostal') }}">
                            </div>

                            <div class="col-span-2">
                                <label class="block font-bold text-xs text-gray-500 mb-1">Ville</label>
                                <input
                                    type="text"
                                    id="locality_display"
                                    value="{{ old('ville') }}"
                                    class="w-full bg-gray-100 border-gray-200 rounded-[10px] py-2 px-3 text-gray-600"
                                    disabled
                                >
                                <input type="hidden" id="ville" name="ville" value="{{ old('ville') }}">
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-5">
                        <h4 class="font-bold text-gray-900 mb-4 text-lg">Identifiants</h4>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Pseudonyme *</label>
                                <input
                                    type="text"
                                    name="pseudo"
                                    value="{{ old('pseudo') }}"
                                    class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0"
                                    required
                                >
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Téléphone *</label>
                                <input
                                    type="text"
                                    name="telephone"
                                    value="{{ old('telephone') }}"
                                    class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0"
                                    required
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ show: false }">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Mot de passe *</label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 text-gray-900 pr-10"
                                        :type="show ? 'text' : 'password'"
                                        name="password"
                                        required
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">Confirmer *</label>
                                <div class="relative">
                                    <input
                                        id="password_confirmation"
                                        class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 text-gray-900 pr-10"
                                        :type="show ? 'text' : 'password'"
                                        name="password_confirmation"
                                        required
                                    >
                                    <button
                                        type="button"
                                        @click="show = !show"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer transition"
                                    >
                                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-[#ec5a13] hover:bg-[#d64d0e] text-white font-bold py-3 px-4 rounded-[18px] transition duration-200 text-center shadow-md hover:shadow-lg">
                            Créer mon compte
                        </button>
                    </div>
                </form>
            </div>
        </x-authentication-card>
    </div>

    {{-- SCRIPT GOOGLE MAPS --}}
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
                const villeHidden = document.getElementById('ville');

                if (numHidden)   numHidden.value   = streetNumber || 1;
                if (routeHidden) routeHidden.value = route || '';
                if (cpHidden)    cpHidden.value    = postalCode || '';
                if (villeHidden) villeHidden.value = city || '';
            });
        }

        window.initAutocomplete = initAutocomplete;
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
</x-guest-layout>
