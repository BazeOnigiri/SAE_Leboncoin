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

                <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-blue-500 before:via-gray-200 before:to-gray-200">
                    
                    {{-- Étape 1 à 4 (Boucle simplifiée pour l'exemple) --}}
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
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow-sm shrink-0 z-10 {{ $incident->etape >= $num ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                @if($incident->etape > $num)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                @else
                                    <span class="text-xs font-bold">{{ $num }}</span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold {{ $incident->etape >= $num ? 'text-gray-900' : 'text-gray-400' }}">{{ $info['Titre'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $info['Desc'] }}</p>
                                
                                @if($num == 4 && $incident->etape == 4 && !$incident->estclasse && !$incident->estremisaucontentieux)
                                    <div class="mt-4 p-4 bg-blue-50 rounded-2xl border border-blue-100 flex gap-3">
                                        <form action="{{ route('incidents.accepter', $incident->idincident) }}" method="POST">
                                            @csrf
                                            <button class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition">Accepter</button>
                                        </form>
                                        <form action="{{ route('incidents.contester', $incident->idincident) }}" method="POST">
                                            @csrf
                                            <button class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-50 transition">Contester</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($incident->estclasse && !$incident->estremisaucontentieux)
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow-sm shrink-0 z-10 bg-green-500 text-white">
                                🏁
                            </div>
                            <div class="ml-4 text-green-700 font-bold">Dossier clôturé avec succès.</div>
                        </div>
                    @endif

                    @if($incident->estremisaucontentieux)
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow-sm shrink-0 z-10 bg-red-600 text-white">
                                ⚖️
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold text-red-600">Expertise contentieuse</h3>
                                <p class="text-sm text-gray-500 italic">Le dossier est entre les mains du service juridique.</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>