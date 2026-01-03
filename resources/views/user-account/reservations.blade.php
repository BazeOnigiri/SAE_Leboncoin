<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes réservations</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8" x-data="{ 
        activeTab: 'upcoming', 
        showCancelModal: false, 
        reservationToCancel: null, 
        reservationTitle: '',
        showEditModal: false,
        editReservation: {
            id: null,
            title: '',
            adultes: 1,
            enfants: 0,
            bebes: 0,
            nomClient: '',
            prenomClient: '',
            telephoneClient: '',
            capaciteMax: 1
        }
    }">
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
                            @php
                                $dateDebut = \Carbon\Carbon::parse($reservation->dateDebut->date);
                                $dateFin = \Carbon\Carbon::parse($reservation->dateFin->date);
                                $nbNuits = max(1, $dateDebut->diffInDays($dateFin));
                                $prixNuitee = $reservation->annonce->prixnuitee ?? 0;
                                $totalRent = $prixNuitee * $nbNuits;
                                $serviceFee = $totalRent * 0.14;
                                $adultsCount = $reservation->adultes ?? $reservation->nombrevoyageur ?? 1;
                                $touristTax = 4.00 * $nbNuits * $adultsCount;
                                $montantEstime = $totalRent + $serviceFee + $touristTax;
                                $transactionMontant = $reservation->transaction->montanttransaction ?? null;
                                $affichageMontant = number_format($transactionMontant ?? $montantEstime, 2, ',', ' ');
                            @endphp
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
                                            @if(!is_null($transactionMontant))
                                                <span class="font-bold text-gray-900 text-lg">{{ $affichageMontant }} €</span>
                                            @else
                                                <span class="font-bold text-orange-600 text-lg">En attente de paiement · {{ $affichageMontant }} €</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                            </svg>                        
                                            {{ $reservation->annonce->adresse->ville->nomville ?? 'Ville inconnue' }}
                                        </p>
                                        <div class="mt-3 flex gap-4 text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Du {{ \Carbon\Carbon::parse($reservation->dateDebut->date)->format('d/m') }} au {{ \Carbon\Carbon::parse($reservation->dateFin->date)->format('d/m/Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $reservation->nombrevoyageur ?? $reservation->annonce->capacite ?? 1 }} voyageur{{ ($reservation->nombrevoyageur ?? 1) > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6 mt-4 pt-3 border-t border-gray-100 text-sm font-bold">
                                        @php
                                            $unreadCount = $reservation->messagesNonLusPour(Auth::id());
                                        @endphp
                                        <a href="{{ route('conversation.show', $reservation) }}" class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group relative">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            Message
                                            @if($unreadCount > 0)
                                                <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                                    +{{ $unreadCount }}
                                                </span>
                                            @endif
                                        </a>
                                        <button 
                                            @click="showEditModal = true; editReservation = {
                                                id: {{ $reservation->idreservation }},
                                                title: '{{ addslashes($reservation->annonce->titreannonce ?? 'cette réservation') }}',
                                                adultes: {{ $reservation->adultes ?? $reservation->nombrevoyageur ?? $reservation->annonce->capacite ?? 1 }},
                                                enfants: {{ $reservation->enfants ?? 0 }},
                                                bebes: {{ $reservation->bebes ?? 0 }},
                                                nomClient: '{{ addslashes($reservation->nomclient ?? '') }}',
                                                prenomClient: '{{ addslashes($reservation->prenomclient ?? '') }}',
                                                telephoneClient: '{{ addslashes($reservation->telephoneclient ?? '') }}',
                                                capaciteMax: {{ $reservation->annonce->capacite ?? 20 }}
                                            }"
                                            class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Modifier
                                        </button>
                                        <button class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Détails
                                        </button>
                                        <button class="text-gray-700 hover:text-blue-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Facture
                                        </button>
                                        <a href="{{ route('incidents.create', $reservation) }}" class="text-gray-700 hover:text-red-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            Signaler un incident
                                        </a>
                                        <button 
                                            @click="showCancelModal = true; reservationToCancel = {{ $reservation->idreservation }}; reservationTitle = '{{ addslashes($reservation->annonce->titreannonce ?? 'cette réservation') }}'"
                                            class="text-gray-700 hover:text-red-600 flex items-center gap-1 transition ml-auto group">
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
                            @php
                                $dateDebut = \Carbon\Carbon::parse($reservation->dateDebut->date);
                                $dateFin = \Carbon\Carbon::parse($reservation->dateFin->date);
                                $nbNuits = max(1, $dateDebut->diffInDays($dateFin));
                                $prixNuitee = $reservation->annonce->prixnuitee ?? 0;
                                $totalRent = $prixNuitee * $nbNuits;
                                $serviceFee = $totalRent * 0.14;
                                $adultsCount = $reservation->adultes ?? $reservation->nombrevoyageur ?? 1;
                                $touristTax = 4.00 * $nbNuits * $adultsCount;
                                $montantEstime = $totalRent + $serviceFee + $touristTax;
                                $transactionMontant = $reservation->transaction->montanttransaction ?? null;
                                $affichageMontant = number_format($transactionMontant ?? $montantEstime, 2, ',', ' ');
                            @endphp
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
                                            <h3 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $reservation->annonce->titreannonce ?? 'Logement' }}</h3>
                                            @if(!is_null($transactionMontant))
                                                <span class="font-bold text-gray-900 text-lg">{{ $affichageMontant }} €</span>
                                            @else
                                                <span class="font-bold text-orange-600 text-lg">En attente de paiement · {{ $affichageMontant }} €</span>
                                            @endif
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
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $reservation->nombrevoyageur ?? $reservation->annonce->capacite ?? 1 }} voyageur{{ ($reservation->nombrevoyageur ?? 1) > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6 mt-4 pt-3 border-t border-gray-100 text-sm font-bold">
                                        @php
                                            $unreadCountPassee = $reservation->messagesNonLusPour(Auth::id());
                                        @endphp
                                        <a href="{{ route('conversation.show', $reservation) }}" class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group relative">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            Message
                                            @if($unreadCountPassee > 0)
                                                <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                                    +{{ $unreadCountPassee }}
                                                </span>
                                            @endif
                                        </a>
                                        <button class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Revoir détails
                                        </button>
                                        <button class="text-gray-700 hover:text-blue-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
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

        <div x-show="showCancelModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="display: none;">
            <div class="fixed inset-0 bg-black/50" @click="showCancelModal = false"></div>
            
            <div x-show="showCancelModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 z-10">
                
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmer l'annulation</h3>
                    <p class="text-gray-600 mb-6">
                        Êtes-vous sûr de vouloir annuler votre réservation pour 
                        <span class="font-semibold" x-text="reservationTitle"></span> ?
                        <br><span class="text-sm text-gray-500 mt-2 block">Cette action est irréversible.</span>
                    </p>
                </div>

                <div class="flex gap-3">
                    <button @click="showCancelModal = false" 
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                        Annuler
                    </button>
                    <form method="POST" class="flex-1" x-bind:action="'{{ url('/reservations') }}/' + reservationToCancel + '/cancel'">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition">
                            Confirmer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showEditModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="display: none;">
            <div class="fixed inset-0 bg-black/50" @click="showEditModal = false"></div>
            
            <div x-show="showEditModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 z-10">
                
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Modifier la réservation</h3>
                    <p class="text-gray-600" x-text="editReservation.title"></p>
                </div>

                <form method="POST" x-bind:action="'{{ url('/reservations') }}/' + editReservation.id">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div x-data="{ get totalCounted() { return (editReservation.adultes || 0) + (editReservation.enfants || 0); } }">
                            <label for="adultes" class="block text-sm font-medium text-gray-700 mb-1">Adultes</label>
                            <div class="text-xs text-gray-500 mb-2">18 ans et plus</div>
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="if(editReservation.adultes > 1) editReservation.adultes--"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold transition">
                                    -
                                </button>
                                <input type="number" name="adultes" id="adultes" 
                                        x-model.number="editReservation.adultes"
                                        min="1" :max="editReservation.capaciteMax - editReservation.enfants"
                                        class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:ring-orange-500 focus:border-orange-500">
                                <button type="button" 
                                        @click="if(totalCounted < editReservation.capaciteMax) editReservation.adultes++"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold transition">
                                    +
                                </button>
                            </div>
                        </div>

                        <div x-data="{ get totalCounted() { return (editReservation.adultes || 0) + (editReservation.enfants || 0); } }">
                            <label for="enfants" class="block text-sm font-medium text-gray-700 mb-1">Enfants</label>
                            <div class="text-xs text-gray-500 mb-2">De 2 à 17 ans</div>
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="if(editReservation.enfants > 0) editReservation.enfants--"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold transition">
                                    -
                                </button>
                                <input type="number" name="enfants" id="enfants" 
                                        x-model.number="editReservation.enfants"
                                        min="0" :max="Math.max(0, editReservation.capaciteMax - editReservation.adultes)"
                                        class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:ring-orange-500 focus:border-orange-500">
                                <button type="button" 
                                        @click="if(totalCounted < editReservation.capaciteMax) editReservation.enfants++"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold transition">
                                    +
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="bebes" class="block text-sm font-medium text-gray-700 mb-1">Bébés</label>
                            <div class="text-xs text-gray-500 mb-2">Moins de 3 ans</div>
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="if(editReservation.bebes > 0) editReservation.bebes--"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold transition">
                                    -
                                </button>
                                    <input type="number" name="bebes" id="bebes" 
                                        x-model.number="editReservation.bebes"
                                        min="0" :max="6"
                                        class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:ring-orange-500 focus:border-orange-500">
                                    <button type="button" 
                                        @click="if(editReservation.bebes < 6) editReservation.bebes++"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold transition">
                                    +
                                </button>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            Capacité maximale : <span x-text="editReservation.capaciteMax"></span> personnes | Maximum 6 bébés | Au moins 1 adulte requis
                        </p>

                        <div>
                            <label for="prenom_client" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" name="prenom_client" id="prenom_client" 
                                    x-model="editReservation.prenomClient"
                                    required maxlength="50"
                                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <div>
                            <label for="nom_client" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" name="nom_client" id="nom_client" 
                                    x-model="editReservation.nomClient"
                                    required maxlength="50"
                                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <div>
                            <label for="telephone_client" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" name="telephone_client" id="telephone_client" 
                                    x-model="editReservation.telephoneClient"
                                    maxlength="20"
                                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showEditModal = false" 
                                class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-xl font-bold hover:bg-orange-700 transition">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>