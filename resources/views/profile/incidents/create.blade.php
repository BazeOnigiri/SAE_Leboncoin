<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Signaler un incident
        </h2>
    </x-slot>

    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Déclarer un problème sur votre réservation</h1>
            
            <div class="bg-orange-50 border-l-4 border-orange-500 text-orange-700 p-4 mb-6" role="alert">
                <p class="font-bold">Réservation n°{{ $reservation->idreservation }}</p>
                <p class="text-sm">Hébergement : **{{ $reservation->annonce->titreannonce ?? 'Détails non trouvés' }}**</p>
                <p class="text-sm">Dates : Du **{{ \Carbon\Carbon::parse($reservation->dateDebut->date)->format('d/m/Y') }}** au **{{ \Carbon\Carbon::parse($reservation->dateFin->date)->format('d/m/Y') }}**</p>
            </div>
            
            {{-- Affichage des messages de validation ou de succès --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('incidents.store', $reservation) }}" method="POST" class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                @csrf

                <div class="mb-5">
                    <label for="motif" class="block text-gray-700 text-sm font-bold mb-2">
                        Motif de l'incident <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="motif" 
                        id="motif" 
                        value="{{ old('motif') }}"
                        maxlength="100"
                        placeholder="Ex : Problème d'accès à la propriété, Logement non conforme"
                        required 
                        class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-600 @error('motif') border-red-500 @enderror"
                    >
                    <p class="text-xs text-gray-500 mt-1">{{ 100 - strlen(old('motif')) }} caractères restants (max 100)</p>
                    @error('motif')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                        Description détaillée de l'incident <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="8"
                        maxlength="2000"
                        placeholder="Décrivez précisément ce qui s'est passé, les dates et les conséquences du problème."
                        required 
                        class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-600 @error('description') border-red-500 @enderror"
                    >{{ old('description') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">{{ 2000 - strlen(old('description')) }} caractères restants (max 2000)</p>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="block text-gray-700 text-sm font-bold mb-3">
                        Compensations souhaitées (Sélectionnez une ou plusieurs)
                    </label>
                    <div class="space-y-3">
                        @forelse($compensations as $compensation)
                            <div class="flex items-start">
                                <input 
                                    type="checkbox" 
                                    name="compensations[]" 
                                    id="compensation_{{ $compensation->idcompensation }}" 
                                    value="{{ $compensation->idcompensation }}"
                                    class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                    {{ (is_array(old('compensations')) && in_array($compensation->idcompensation, old('compensations'))) ? 'checked' : '' }}
                                >
                                <div class="ml-3 text-sm">
                                    <label for="compensation_{{ $compensation->idcompensation }}" class="font-medium text-gray-900 cursor-pointer">
                                        {{ $compensation->nomcompensation }}
                                    </label>
                                    <p class="text-gray-500">{{ $compensation->descriptioncompensation ?? 'Détail de la compensation.' }}</p>
                                </div>
                            </div>
                        @empty
                             <p class="text-gray-500 italic">Aucune option de compensation n'est actuellement disponible.</p>
                        @endforelse
                    </div>
                    @error('compensations')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4">
                    {{-- Bouton Annuler : Redirige vers la page précédente (vos réservations) --}}
                    <a href="{{ url()->previous() }}" class="text-gray-600 font-bold py-2 px-4 rounded hover:text-gray-800 transition">
                        Annuler
                    </a>
                    
                    {{-- Bouton Confirmer : Soumet le formulaire --}}
                    <button 
                        type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                        Confirmer le Signalement
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>