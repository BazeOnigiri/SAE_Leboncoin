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
                                <input type="radio" name="civilite" value="Madame" class="text-orange-600 focus:ring-orange-600" 
                                    {{ optional($user->particulier)->civilite == 'Madame' ? 'checked' : '' }}>
                                <span>Madame</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="civilite" value="Monsieur" class="text-orange-600 focus:ring-orange-600"
                                    {{ optional($user->particulier)->civilite == 'Monsieur' ? 'checked' : '' }}>
                                <span>Monsieur</span>
                            </label>
                        </div>
                        @error('civilite') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom', $user->nomutilisateur) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                            @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenomutilisateur) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                            @error('prenom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date de naissance</label>
                        <input type="date" name="date_naissance" 
                            value="{{ old('date_naissance', optional(optional($user->particulier)->dateNaissance)->date) }}" 
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        @error('date_naissance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone</label>
                        <input type="tel" name="telephone" value="{{ old('telephone', $user->telephoneutilisateur) }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        @error('telephone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <hr class="my-8 border-gray-200">

                    <h2 class="text-xl font-bold mb-6">Coordonnées</h2>

                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">N°</label>
                            <input type="number" name="numerorue" value="{{ old('numerorue', $user->adresse->numerorue ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Voie</label>
                            <input type="text" name="nomrue" value="{{ old('nomrue', $user->adresse->nomrue ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Nom de la rue">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Code postal</label>
                            <input type="text" name="codepostal" value="{{ old('codepostal', $user->adresse->ville->codepostal ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                            @error('codepostal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ville</label>
                            <input type="text" name="nomville" value="{{ old('nomville', $user->adresse->ville->nomville ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                            @error('nomville') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-orange-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-orange-700 transition shadow-sm">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>