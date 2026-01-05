<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion du Catalogue') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f8f9fb] min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alertes de succès ou d'erreur --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
                    <span class="font-bold">Succès !</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
                    <span class="font-bold">Erreur :</span> {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- SECTION 1 : ÉQUIPEMENTS --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl shadow-inner">
                            🔌
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight">Nouvel Équipement</h3>
                            <p class="text-xs text-gray-400 font-medium">Ajout dans la table commodite</p>
                        </div>
                    </div>

                    <form action="{{ route('services.catalogue.add.equipement') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nom de l'équipement</label>
                                <input type="text" name="nom" required 
                                    class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nom') border-red-500 @enderror" 
                                    placeholder="Ex: Climatisation, Wifi, BBQ..."
                                    value="{{ old('nom') }}">
                                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Ajouter au catalogue
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SECTION 2 : TYPES D'HÉBERGEMENT --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-2xl shadow-inner">
                            🏠
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight">Nouveau Logement</h3>
                            <p class="text-xs text-gray-400 font-medium">Ajout dans commodite & typehebergement</p>
                        </div>
                    </div>

                    <form action="{{ route('services.catalogue.add.hebergement') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nom du type d'hébergement</label>
                                <input type="text" name="nom" required 
                                    class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('nom') border-red-500 @enderror" 
                                    placeholder="Ex: Péniche, Yourte, Loft..."
                                    value="{{ old('nom') }}">
                                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="w-full bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Enregistrer le type
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- Footer d'aide --}}
            <div class="mt-12 bg-white rounded-2xl p-6 border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center font-bold">?</div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Besoin d'aide ?</p>
                        <p class="text-xs text-gray-500">Les nouveaux types apparaîtront automatiquement lors de la création d'annonce.</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-gray-900 font-bold transition">← Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>