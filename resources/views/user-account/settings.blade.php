<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Paramètres</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Paramètres</h1>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold mb-6">Informations personnelles</h2>
                
                <form>
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
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
                            <input type="text" value="{{ $user->nomutilisateur }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50 text-gray-500" disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
                            <input type="text" value="{{ $user->prenomutilisateur }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 bg-gray-50 text-gray-500" disabled>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date de naissance</label>
                        <input type="date" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <hr class="my-8 border-gray-200">

                    <h2 class="text-xl font-bold mb-6">Coordonnées</h2>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Adresse</label>
                        <input type="text" value="{{ $user->adresse->nomrue ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Numéro et nom de voie">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Code postal</label>
                            <input type="text" value="{{ $user->adresse->ville->codepostal ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ville</label>
                            <input type="text" value="{{ $user->adresse->ville->nomville ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="button" class="bg-orange-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-orange-700 transition shadow-sm">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>