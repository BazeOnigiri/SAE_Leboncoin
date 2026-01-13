<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes signalements d'incidents</h2>
    </x-slot>

    <div class="py-12 bg-[#f8f9fb] min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Suivi de mes litiges</h1>

            @if($incidents->isEmpty())
                <div class="bg-white p-12 rounded-2xl shadow-sm text-center border border-gray-200">
                    <div class="text-5xl mb-4">✅</div>
                    <h3 class="text-xl font-bold text-gray-900">Aucun incident signalé</h3>
                    <p class="text-gray-500 mt-2">Tout semble se passer pour le mieux dans vos voyages.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($incidents as $incident)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold">
                                    #{{ $incident->idincident }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $incident->motifincident }}</h4>
                                    <p class="text-sm text-gray-500">
                                        Sur le logement : {{ $incident->reservation->annonce->titreannonce ?? 'Logement' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                                {{-- Badge d'étape --}}
                                <div>
                                    @if($incident->estremisaucontentieux)
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase tracking-widest italic">⚖️ Contentieux</span>
                                    @elseif($incident->estclasse)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold uppercase tracking-widest">Terminé</span>
                                    @else
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-widest italic">Étape {{ $incident->etape }} / 4</span>
                                    @endif
                                </div>

                                <a href="{{ route('incidents.suivi', $incident->idincident) }}" class="text-orange-600 font-bold text-sm hover:underline">
                                    Voir le détail →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>