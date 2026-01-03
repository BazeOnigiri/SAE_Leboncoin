<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Conversation
        </h2>
    </x-slot>

    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-lg">
                            @php
                                $interlocuteurAvatar = $interlocuteur->profile_photo_url
                                    ?? 'https://ui-avatars.com/api/?name=' . urlencode($interlocuteur->pseudonyme ?? 'Utilisateur');
                            @endphp
                            <img src="{{ $interlocuteurAvatar }}" alt="Photo de profil" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">
                                @if(!empty($interlocuteur?->idutilisateur))
                                    <a href="{{ route('user.profile', ['id' => $interlocuteur->idutilisateur]) }}" class="hover:text-orange-600 hover:underline transition">
                                        {{ $interlocuteur->pseudonyme ?? 'Utilisateur' }}
                                    </a>
                                @else
                                    {{ $interlocuteur->pseudonyme ?? 'Utilisateur' }}
                                @endif
                            </h1>
                            <p class="text-sm text-gray-500">
                                @if($isClient)
                                    Propriétaire de l'annonce
                                @else
                                    Client - Réservation #{{ $reservation->idreservation }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ $isClient ? route('user.mes-reservations') : route('user.annonces') }}" 
                        class="text-gray-500 hover:text-gray-700 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-4 rounded-r-xl">
                <div class="flex items-start gap-4">
                    @if($reservation->annonce->photos->isNotEmpty())
                        <img src="{{ $reservation->annonce->photos->first()->lienphoto }}" 
                            alt="Photo de l'annonce" 
                            class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 truncate">{{ $reservation->annonce->titreannonce }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $reservation->annonce->adresse->ville->nomville ?? 'Ville inconnue' }}
                        </p>
                        <p class="text-sm text-orange-700 font-medium mt-1">
                            Du {{ \Carbon\Carbon::parse($reservation->dateDebut->date)->format('d/m/Y') }} 
                            au {{ \Carbon\Carbon::parse($reservation->dateFin->date)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                <div class="p-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-700">Messages</h2>
                </div>
                
                <div class="p-4 space-y-4 max-h-[500px] overflow-y-auto" id="messages-container">
                    @forelse($messages as $message)
                        @php
                            $isOwnMessage = $message->idutilisateurexpediteur === Auth::id();
                            $avatarUrl = $message->expediteur->profile_photo_url
                                ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->expediteur->pseudonyme ?? 'Utilisateur');
                        @endphp
                        
                        <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }} items-end gap-2">
                            @if(!$isOwnMessage)
                                <img src="{{ $avatarUrl }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            @endif
                            <div class="max-w-[75%] {{ $isOwnMessage ? 'order-2' : 'order-1' }}">
                                @if(!$isOwnMessage)
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $message->expediteur->pseudonyme ?? 'Utilisateur' }}</span>
                                    </div>
                                @endif
                                
                                <div class="rounded-2xl px-4 py-3 {{ $isOwnMessage ? 'bg-orange-600 text-white rounded-br-md' : 'bg-gray-100 text-gray-800 rounded-bl-md' }}">
                                    <p class="text-sm whitespace-pre-wrap break-words">{{ $message->contenumessage }}</p>
                                </div>
                                
                                <div class="flex items-center gap-1 mt-1 {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                                    <span class="text-xs text-gray-400">
                                        {{ $message->created_at ? $message->created_at->format('d/m/Y à H:i') : '' }}
                                    </span>
                                    @if($isOwnMessage && $message->lu)
                                        <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            @if($isOwnMessage)
                                <img src="{{ $avatarUrl }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4">💬</div>
                            <h3 class="text-lg font-semibold text-gray-700">Aucun message</h3>
                            <p class="text-gray-500 text-sm mt-1">Commencez la conversation !</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('conversation.store', $reservation) }}" method="POST" class="flex gap-3">
                    @csrf
                    
                    <div class="flex-1">
                        <textarea 
                            name="message" 
                            id="message"
                            rows="2"
                            maxlength="1000"
                            placeholder="Écrivez votre message..."
                            required
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring-orange-500 resize-none text-sm @error('message') border-red-500 @enderror"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" 
                            class="self-end bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Envoyer
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ $isClient ? route('user.mes-reservations') : route('user.annonces') }}" 
                    class="text-gray-600 hover:text-orange-600 font-medium inline-flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à {{ $isClient ? 'mes réservations' : 'mes annonces' }}
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
</x-app-layout>
