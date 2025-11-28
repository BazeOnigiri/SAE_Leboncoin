<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ route('auth.check') }}" class="flex items-center gap-2 text-gray-600 hover:text-orange-600 font-medium transition">
            <i class="fa-solid fa-arrow-left"></i> 
            <span>Retour</span>
        </a>
    </div>
    
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />


        <div x-data="{ 
            step: 1, 
            role: 'particulier',
            selectRole(type) {
                this.role = type;
                this.step = 2;
            }
        }">

            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Créez un compte</h2>
                <p class="text-sm text-gray-600 mb-8">
                    Bénéficiez d'une expérience personnalisée avec du contenu en lien avec votre activité.
                </p>

                <div class="space-y-4">
                    <div @click="selectRole('particulier')" class="cursor-pointer group border rounded-lg p-4 hover:border-orange-500 hover:bg-orange-50 transition flex items-center">
                        <div class="h-6 w-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 group-hover:border-orange-500">
                            <div class="h-3 w-3 rounded-full bg-orange-500 opacity-0 group-hover:opacity-100 transition"></div>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800">Pour vous <span class="text-orange-600">*</span></span>
                        </div>
                    </div>

                    <div @click="selectRole('professionnel')" class="cursor-pointer group border rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition flex items-center">
                        <div class="h-6 w-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 group-hover:border-blue-500">
                            <div class="h-3 w-3 rounded-full bg-blue-500 opacity-0 group-hover:opacity-100 transition"></div>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800">Pour votre entreprise</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-xs text-gray-500">
                    <p class="mb-4">* Vous agissez à titre professionnel ? <a href="#" @click.prevent="selectRole('professionnel')" class="underline font-bold text-gray-800">Créez plutôt un compte pro !</a></p>
                    <hr class="my-4">
                    <p class="text-center text-sm">
                        Vous avez déjà un compte ? <a href="{{ route('auth.check') }}" class="underline font-bold text-gray-900">Me connecter</a>
                    </p>
                </div>
            </div>


            <form x-show="step === 2" method="POST" action="{{ route('register') }}" x-cloak>
                @csrf
                
                <input type="hidden" name="email" value="{{ request('email') }}">
                <input type="hidden" name="role" :value="role">

                <button type="button" @click="step = 1" class="text-sm text-gray-500 hover:text-gray-800 mb-6 flex items-center font-medium">
                    <i class="fa-solid fa-chevron-left mr-2"></i> Changer de type de compte
                </button>


                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4" x-text="role === 'particulier' ? 'Commençons par un e-mail' : 'Commençons par votre entreprise'"></h3>
                    <div class="relative">
                        <x-label value="E-mail *" />
                        <x-input class="block mt-1 w-full bg-gray-50 text-gray-500 cursor-not-allowed" type="email" disabled value="{{ request('email') }}" />
                        <i class="fa-solid fa-lock absolute right-3 top-9 text-gray-400"></i>
                    </div>
                </div>


                <div x-show="role === 'particulier'">
                    <h4 class="font-bold text-gray-900 mb-4 text-lg">Paramètres</h4>
                    
                    <div class="mb-4">
                        <span class="block font-medium text-sm text-gray-700 mb-2">Informations de compte</span>
                        <div class="flex gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="civilite" value="Monsieur" class="form-radio text-gray-900 focus:ring-gray-900" checked>
                                <span class="ml-2 text-gray-700">Monsieur</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="civilite" value="Madame" class="form-radio text-gray-900 focus:ring-gray-900">
                                <span class="ml-2 text-gray-700">Madame</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-label for="nom" value="Nom" />
                            <x-input id="nom" class="block mt-1 w-full" type="text" name="nom" />
                        </div>
                        <div>
                            <x-label for="prenom" value="Prénom" />
                            <x-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-label for="date_naissance" value="Date de naissance" />
                        <x-input id="date_naissance" class="block mt-1 w-full" type="date" name="date_naissance" />
                    </div>
                </div>


                <div x-show="role === 'professionnel'">
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-md">
                        <div>
                            <x-label for="numsiret" value="SIRET *" />
                            <x-input id="numsiret" class="block mt-1 w-full border-gray-400" type="text" name="numsiret" maxlength="14" placeholder="14 chiffres" />
                        </div>
                    </div>

                    <h4 class="font-bold text-gray-900 mb-4 text-lg">Continuons avec plus d'informations</h4>

                    <div class="mb-4">
                        <x-label for="nomsociete" value="Société *" />
                        <x-input id="nomsociete" class="block mt-1 w-full uppercase" type="text" name="nomsociete" placeholder="NOM DE L'ENTREPRISE" />
                        <p class="text-xs text-gray-400 mt-1">Le nom de votre société sera visible sur vos annonces</p>
                    </div>

                    <div class="mb-4">
                        <x-label for="secteuractivite" value="Secteur d'activité *" />
                        <div class="relative">
                            <select name="secteuractivite" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm appearance-none">
                                <option value="Vacances">Vacances</option>
                                <option value="Immobilier">Immobilier</option>
                                <option value="Services">Services</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mt-8 border-t pt-6">
                    <h4 class="font-bold text-gray-900 mb-4 text-lg">Adresse</h4>
                    
                    <div class="mb-4">
                        <x-label for="nomrue" value="Adresse *" />
                        <x-input id="nomrue" class="block mt-1 w-full" type="text" name="nomrue" placeholder="Ex: 246 rue de la croix verte" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <x-label for="codepostal" value="Code postal *" />
                            <x-input id="codepostal" class="block mt-1 w-full" type="text" name="codepostal" placeholder="01750" required />
                        </div>
                        <div class="relative">
                            <x-label value="Ville *" />
                            <x-input class="block mt-1 w-full" type="text" placeholder="CHAMBERY" required />
                        </div>
                        <input type="hidden" name="numerorue" value="1">
                    </div>

                    <h4 class="font-bold text-gray-900 mb-4 text-lg">Identifiants</h4>

                    <div class="mb-4">
                        <x-label for="pseudo" value="Pseudonyme *" />
                        <x-input id="pseudo" class="block mt-1 w-full" type="text" name="pseudo" required />
                    </div>

                    <div class="mb-4">
                        <x-label for="telephone" value="Téléphone *" />
                        <x-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" required />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-label for="password" value="Mot de passe *" />
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>
                        <div>
                            <x-label for="password_confirmation" value="Confirmer *" />
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <x-button class="w-full justify-center h-12 text-lg bg-orange-600 hover:bg-orange-700 shadow-sm">
                        {{ __('Continuer') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>