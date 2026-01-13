<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suivi de mon incident') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f8f9fb] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- En-tête de la carte --}}
                <div class="px-8 py-6 border-b border-gray-100 bg-white">
                    <h3 class="text-2xl font-bold text-gray-900 text-center">État de votre signalement</h3>
                    <p class="text-center text-gray-500 text-sm mt-1">Référence Réservation : #{{ $reservation->idreservation }}</p>
                </div>

                <div class="p-8">
                    {{-- Barre de progression dynamique --}}
                    <div class="relative flex justify-between items-center mb-12">
                        @php 
                            $steps = [
                                1 => 'Signalé', 
                                2 => 'Vérification', 
                                3 => 'Justification', 
                                4 => 'Décision'
                            ]; 
                            // On plafonne l'affichage à l'étape 4 pour la barre de progression
                            $currentStepDisplay = min($incident->etape, 4);
                        @endphp

                        @foreach($steps as $num => $label)
                            <div class="flex flex-col items-center z-10">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors duration-500 {{ $num <= $currentStepDisplay ? 'bg-orange-600 text-white shadow-lg shadow-orange-200' : 'bg-gray-200 text-gray-500' }}">
                                    @if($num < $currentStepDisplay)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        {{ $num }}
                                    @endif
                                </div>
                                <span class="text-xs mt-3 font-bold uppercase tracking-wider {{ $num <= $currentStepDisplay ? 'text-orange-600' : 'text-gray-400' }}">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach

                        {{-- Lignes de connexion --}}
                        <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-100 -z-0"></div>
                        <div class="absolute top-5 left-0 h-0.5 bg-orange-600 transition-all duration-700 ease-in-out -z-0" 
                             style="width: {{ ($currentStepDisplay - 1) * 33.33 }}%"></div>
                    </div>

                    {{-- Contenu central selon l'étape --}}
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 relative">
                        
                        @if($incident->etape < 4)
                            {{-- ÉTAPES 1, 2, 3 : EN COURS --}}
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4 animate-pulse">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">Analyse en cours</h4>
                                <p class="text-gray-600 leading-relaxed">
                                    Votre demande est actuellement examinée par notre service de modération. 
                                    Nous avons sollicité les parties nécessaires pour rendre une décision équitable.
                                </p>
                            </div>

                        @else
                            {{-- ÉTAPE 4 ET PLUS : DÉCISION RENDUE --}}
                            <div class="text-center">
                                @if($incident->estrembourse)
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-green-700 mb-2">Remboursement Validé</h4>
                                    <p class="text-gray-600 mb-6">Bonne nouvelle ! Votre demande de remboursement a été acceptée après étude du dossier.</p>
                                @else
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 text-red-600 rounded-full mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-red-700 mb-2">Remboursement Refusé</h4>
                                    <p class="text-gray-600 mb-6">Après analyse des éléments, nos services n'ont pas pu valider votre demande de remboursement.</p>
                                @endif

                                {{-- ACTIONS POSSIBLES (Uniquement à l'étape 4) --}}
                                @if($incident->etape == 4)
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center mt-6">
                                        @if(!$incident->estrembourse)
                                            <form action="{{ route('incidents.contester', $reservation) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full sm:w-auto border-2 border-orange-600 text-orange-600 px-6 py-2.5 rounded-xl font-bold hover:bg-orange-50 transition transform active:scale-95">
                                                    Contester la décision
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('incidents.cloturer', $reservation) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-black transition shadow-lg transform active:scale-95">
                                                Accepter et Fermer
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                {{-- MESSAGES DE FIN (Étapes 5 et 6) --}}
                                @if($incident->etape == 5)
                                    <div class="mt-4 p-4 bg-blue-50 text-blue-800 rounded-xl border border-blue-100 font-medium">
                                        <p class="flex items-center justify-center gap-2">
                                            <span class="animate-spin text-lg">⚙️</span>
                                            Dossier en cours de révision suite à votre contestation.
                                        </p>
                                    </div>
                                @elseif($incident->etape == 6)
                                    <div class="mt-4 p-4 bg-gray-100 text-gray-500 rounded-xl border border-gray-200 font-bold uppercase tracking-widest text-sm">
                                        Incident Clôturé
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="mt-10 text-center">
                        <a href="{{ route('user.mes-reservations') }}" class="inline-flex items-center text-gray-400 hover:text-orange-600 transition font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Retour à mes voyages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>