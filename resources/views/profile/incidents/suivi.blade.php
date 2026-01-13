<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Suivi du litige #{{ $incident->idincident }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f8f9fb] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('user.incidents.index') }}" class="text-sm text-gray-500 hover:text-gray-900 flex items-center gap-2">
                    ← Retour à mes litiges
                </a>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-200">
                <div class="mb-10 text-center">
                    <span class="text-xs font-black uppercase tracking-[0.2em] text-red-600">Statut de la réclamation</span>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $incident->motifincident }}</h1>
                    <p class="text-gray-500 text-sm">Logement : {{ $incident->reservation->annonce->titreannonce }}</p>
                </div>

                <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-orange-500 before:via-gray-200 before:to-gray-200">
                    
                    @php
                        $etapes = [
                            1 => ['Titre' => 'Signalement transmis', 'Desc' => 'Votre dossier est en attente de lecture.'],
                            2 => ['Titre' => 'Enquête en cours', 'Desc' => 'Nous avons sollicité le propriétaire.'],
                            3 => ['Titre' => 'Analyse des preuves', 'Desc' => 'Étude des justificatifs fournis.'],
                            4 => ['Titre' => 'Décision finale', 'Desc' => 'La médiation a rendu son verdict.']
                        ];
                    @endphp

                    @foreach($etapes as $num => $info)
                        <div class="relative flex items-start group">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow-sm shrink-0 z-10 {{ $incident->etape >= $num ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                @if($incident->etape > $num)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                @else
                                    <span class="text-xs font-bold">{{ $num }}</span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold {{ $incident->etape >= $num ? 'text-gray-900' : 'text-gray-400' }}">{{ $info['Titre'] }}</h3>
                                
                                @if($num == 4 && $incident->etape >= 4)
                                    <div class="mt-1">
                                        @if($incident->estrembourse)
                                            <p class="text-sm text-orange-600 font-semibold italic">Remboursement validé en votre faveur.</p>
                                        @else
                                            <p class="text-sm text-orange-600 font-semibold italic">Remboursement refusé par nos services.</p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">{{ $info['Desc'] }}</p>
                                @endif
                                
                                @if($num == 4 && $incident->etape == 4 && !$incident->estclasse && !$incident->estremisaucontentieux)
                                    <div class="mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col gap-3">
                                        <p class="text-xs text-gray-500 italic text-center">Souhaitez-vous accepter cette décision ou contester ?</p>
                                        <div class="flex gap-3">
                                            <form action="{{ route('incidents.accepter', $incident->idincident) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button class="w-full bg-orange-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-orange-700 transition shadow-sm">Accepter</button>
                                            </form>
                                            <form action="{{ route('incidents.contester', $incident->idincident) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button class="w-full bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-50 transition">Contester</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($incident->etape == 5 && !$incident->estremisaucontentieux && !$incident->estclasse)
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow-sm shrink-0 z-10 bg-orange-500 text-white animate-pulse">
                                <span class="text-xs font-bold">5</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold text-orange-600">Contestation enregistrée</h3>
                                <p class="text-sm text-gray-600">
                                    Vous avez contesté la décision. Nos agents ré-examinent votre dossier. 
                                    <span class="block mt-1 font-semibold text-gray-700 text-xs uppercase tracking-tight">Le dossier sera prochainement transmis au service juridique si aucun accord n'est trouvé.</span>
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($incident->estclasse && !$incident->estremisaucontentieux)
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow-sm shrink-0 z-10 bg-green-500 text-white">
                                🏁
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold text-green-700">Dossier clôturé</h3>
                                <p class="text-sm text-gray-500">Le litige est désormais terminé.</p>
                            </div>
                        </div>
                    @endif

                    @if($incident->estremisaucontentieux)
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow-sm shrink-0 z-10 bg-red-600 text-white">
                                ⚖️
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold text-red-600 uppercase tracking-tight tracking-widest">Dossier transmis au contentieux</h3>
                                <p class="text-sm text-gray-600 leading-relaxed italic">
                                    La médiation Petite Annonce a échoué. Votre dossier a été officiellement transféré au service juridique.
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>