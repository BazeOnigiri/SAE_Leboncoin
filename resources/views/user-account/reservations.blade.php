<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes réservations</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8" x-data="{ activeTab: 'upcoming' }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Mes voyages</h1>
            
            <div class="flex border-b border-gray-200 mb-6 text-sm font-medium">
                <button 
                    @click="activeTab = 'upcoming'"
                    :class="{ 'border-orange-600 text-orange-600': activeTab === 'upcoming', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'upcoming' }"
                    class="px-6 py-3 border-b-2 font-bold transition-colors duration-200 focus:outline-none">
                    À venir ({{ $reservationsAVenir->count() }})
                </button>
                <button 
                    @click="activeTab = 'history'"
                    :class="{ 'border-orange-600 text-orange-600': activeTab === 'history', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'history' }"
                    class="px-6 py-3 border-b-2 font-bold transition-colors duration-200 focus:outline-none">
                    Historique ({{ $reservationsPassees->count() }})
                </button>
            </div>
            <div x-show="activeTab === 'upcoming'" x-transition.opacity>
                @if($reservationsAVenir->isEmpty())
                    <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                        <div class="text-5xl mb-4">🧳</div>
                        <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucun voyage prévu</h3>
                        <p class="text-gray-500 mt-2 mb-6">Le monde vous attend, commencez à explorer !</p>
                        <a href="/" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Explorer les logements</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($reservationsAVenir as $reservation)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row gap-4 hover:shadow-md transition relative group">
                                <div class="w-full sm:w-48 h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative">
                                    @if($reservation->annonce->photos->isNotEmpty())
                                        <img src="{{ $reservation->annonce->photos->first()->lienphoto }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Sans photo</div>
                                    @endif
                                    <span class="absolute top-2 left-2 bg-blue-100 text-blue-800 text-xs font-bold px-2 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Confirmée
                                    </span>
                                </div>
                                <div class="flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $reservation->annonce->titreannonce ?? 'Logement' }}</h3>
                                            <span class="font-bold text-gray-900 text-lg">{{ $reservation->transaction->montanttransaction ?? '0.00' }} €</span>
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                            </svg>                        
                                            {{ $reservation->annonce->chambres->sum('capacitechambre') }} pers.
                                        </p>
                                        <div class="mt-3 flex gap-4 text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Du {{ \Carbon\Carbon::parse($reservation->dateDebut->date)->format('d/m') }} au {{ \Carbon\Carbon::parse($reservation->dateFin->date)->format('d/m/Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $reservation->voyageurs_count ?? 1 }} voyageurs
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6 mt-4 pt-3 border-t border-gray-100 text-sm font-bold">
                                        <button class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group">
                                            Détails
                                        </button>
                                        <button class="text-gray-700 hover:text-blue-600 flex items-center gap-1 transition group">
                                            Facture
                                        </button>
                                        <button class="text-gray-700 hover:text-red-600 flex items-center gap-1 transition ml-auto group">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
                @endif
            </div>
            <div x-show="activeTab === 'history'" x-transition.opacity style="display: none;">
                @if($reservationsPassees->isEmpty())
                    <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                        <div class="text-5xl mb-4">📜</div>
                        <h3 class="text-xl font-bold text-gray-900">Aucun historique de voyage</h3>
                        <p class="text-gray-500 mt-2">Vos voyages passés apparaîtront ici.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($reservationsPassees as $reservation)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row gap-4 hover:shadow-md transition relative group opacity-75 hover:opacity-100">
                                <div class="w-full sm:w-48 h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative">
                                    @if($reservation->annonce->photos->isNotEmpty())
                                        <img src="{{ $reservation->annonce->photos->first()->lienphoto }}" class="w-full h-full object-cover grayscale">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Sans photo</div>
                                    @endif
                                    <span class="absolute top-2 left-2 bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                        Terminée
                                    </span>
                                </div>
                                <div class="flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $reservation->annonce->titre ?? 'Logement' }}</h3>
                                            <span class="font-bold text-gray-900 text-lg">{{ $reservation->transaction->montanttransaction ?? '0.00' }} €</span>
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $reservation->annonce->adresse->ville->nomville ?? 'Ville inconnue' }}
                                        </p>
                                        <div class="mt-3 flex gap-4 text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Du {{ \Carbon\Carbon::parse($reservation->dateDebut->date)->format('d/m') }} au {{ \Carbon\Carbon::parse($reservation->dateFin->date)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6 mt-4 pt-3 border-t border-gray-100 text-sm font-bold">
                                        <button class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group">
                                            Revoir détails
                                        </button>
                                        <button class="text-gray-700 hover:text-blue-600 flex items-center gap-1 transition group">
                                            Facture
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
    @endsection
</x-app-layout>