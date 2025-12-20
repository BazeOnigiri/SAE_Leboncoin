<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes recherches</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Mes recherches</h1>
            
            @if($searches->isEmpty())
                <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune recherche sauvegardée</h3>
                    <p class="text-gray-500 mt-2 mb-6">Sauvegardez vos critères de recherche pour gagner du temps.</p>
                    <a href="{{ route('home') }}" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Faire une recherche</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($searches as $search)
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="space-y-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        @if($search->ville)
                                            {{ $search->ville->nomville }} ({{ $search->ville->codepostal }})
                                        @elseif($search->departement)
                                            {{ $search->departement->nomdepartement }}
                                        @elseif($search->region)
                                            {{ $search->region->nomregion }}
                                        @else
                                            Toute la France
                                        @endif
                                    </h3>
                                    
                                    @if($search->dateDebut || $search->dateFin)
                                        <div class="text-sm text-gray-600 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                                            </svg>
                                            @if($search->dateDebut && $search->dateFin)
                                                Du {{ \Carbon\Carbon::parse($search->dateDebut->date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($search->dateFin->date)->format('d/m/Y') }}
                                            @elseif($search->dateDebut)
                                                A partir du {{ \Carbon\Carbon::parse($search->dateDebut->date)->format('d/m/Y') }}
                                            @else
                                                Jusqu'au {{ \Carbon\Carbon::parse($search->dateFin->date)->format('d/m/Y') }}
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @if($search->prixminimum || $search->prixmaximum)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-800">
                                                {{ $search->prixminimum ?? 0 }}€ - {{ $search->prixmaximum ?? 'Max' }}€
                                            </span>
                                        @endif
                                        @if($search->capaciteminimumvoyageur)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-800">
                                                {{ $search->capaciteminimumvoyageur }} voyageurs min
                                            </span>
                                        @endif
                                        @if($search->nombreminimumchambre)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-800">
                                                {{ $search->nombreminimumchambre }} chambres min
                                            </span>
                                        @endif
                                        
                                        @foreach($search->typesHebergement as $type)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $type->nomtypehebergement }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <a href="{{ route('home', [
                                        'location' => $search->ville ? $search->ville->nomville : ($search->departement ? $search->departement->nomdepartement : ($search->region ? $search->region->nomregion : '')),
                                        'minPrice' => $search->prixminimum,
                                        'maxPrice' => $search->prixmaximum,
                                        'nbVoyageurs' => $search->capaciteminimumvoyageur ?? 1,
                                        'nbChambres' => $search->nombreminimumchambre ?? 0,
                                        'dateArrivee' => $search->dateDebut ? $search->dateDebut->date : '',
                                        'dateDepart' => $search->dateFin ? $search->dateFin->date : '',
                                        'filterTypes' => $search->typesHebergement->pluck('idtypehebergement')->toArray(),
                                        'selectedCommodites' => $search->commodites->pluck('idcommodite')->toArray(),
                                    ]) }}" class="text-orange-600 hover:text-orange-800 font-medium text-sm">Voir</a>
                                    <form method="POST" action="{{ route('user.searches.delete', $search->idrecherche) }}" onsubmit="return confirm('Supprimer cette recherche ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endsection
</x-app-layout>
