<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Justification d'incident</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl p-8 border border-gray-200">
                
                <h3 class="text-lg font-bold text-red-600 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Incident signalé par {{ $reservation->prenomclient }}
                </h3>

                <div class="bg-gray-50 rounded-lg p-4 mb-8 border-l-4 border-red-500">
                    <p class="text-sm font-bold text-gray-700 uppercase mb-1">Motif : {{ $incident->motifincident }}</p>
                    <p class="text-gray-600 italic">"{{ $incident->descriptionincident }}"</p>
                </div>

                <form action="{{ route('incidents.justification.store', $reservation) }}" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Votre explication</label>
                        <textarea 
                            name="explication" 
                            rows="6" 
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500" 
                            placeholder="Détaillez votre réponse ici..."
                        >{{ old('explication') }}</textarea>
                        @error('explication') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="submit" class="bg-orange-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-orange-700 transition shadow-md">
                            Envoyer l'explication
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>