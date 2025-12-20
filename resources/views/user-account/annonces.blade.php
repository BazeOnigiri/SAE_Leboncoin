<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes annonces</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Mes annonces</h1>
            @php
                $annoncesEnLigne = $annonces->where('estverifie', true);
                $annoncesInactives = $annonces->where('estverifie', false);
            @endphp
            
            <!-- Onglets -->
            <div class="flex border-b border-gray-200 mb-6 text-sm font-medium" id="annonces-tabs">
                <button type="button" data-target="tab-en-ligne" class="tab-btn px-6 py-3 border-b-2 border-orange-600 text-orange-600 font-bold">En ligne ({{ $annoncesEnLigne->count() }})</button>
                <button type="button" data-target="tab-inactives" class="tab-btn px-6 py-3 text-gray-500 hover:text-gray-700">Inactives ({{ $annoncesInactives->count() }})</button>
            </div>

            <div id="tab-en-ligne">
                @if($annoncesEnLigne->isEmpty())
                    <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                        <div class="text-5xl mb-4">📦</div>
                        <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune annonce en ligne</h3>
                        <p class="text-gray-500 mt-2 mb-6">C'est le moment de faire du tri !</p>
                        <a href="#" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Déposer une annonce</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($annoncesEnLigne as $annonce)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row gap-4 hover:shadow-md transition relative group">
                                
                                <!-- Image -->
                                <div class="w-full sm:w-48 h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative">
                                    @if($annonce->photos->isNotEmpty())
                                        <img src="{{ $annonce->photos->first()->lienphoto }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Sans photo</div>
                                    @endif
                                    <span class="absolute top-2 left-2 bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> En ligne
                                    </span>
                                </div>
                                
                                <!-- Contenu -->
                                <div class="flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $annonce->titreannonce }}</h3>
                                            <span class="font-bold text-gray-900 text-lg">{{ $annonce->prixnuitee }} €</span>
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $annonce->adresse->ville->nomville ?? 'Lieu inconnu' }}
                                            <span class="mx-1">•</span>
                                            {{ $annonce->typeHebergement->nomtypehebergement ?? 'Logement' }}
                                        </p>
                                        
                                        <div class="mt-3 flex gap-4 text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> 0 vues</span>
                                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> 0 messages</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-6 mt-4 pt-3 border-t border-gray-100 text-sm font-bold">
                                        <button class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Modifier
                                        </button>
                                        <button class="text-gray-700 hover:text-blue-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            Statistiques
                                        </button>
                                        <button class="text-gray-700 hover:text-red-600 flex items-center gap-1 transition ml-auto group">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-10 hidden" id="tab-inactives">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm font-semibold text-gray-500">Inactives</span>
                    <span class="text-xs bg-gray-200 text-gray-700 rounded-full px-2 py-0.5 font-semibold">{{ $annoncesInactives->count() }}</span>
                </div>

                @if($annoncesInactives->isEmpty())
                    <div class="bg-white p-8 rounded-xl shadow-sm text-center border border-gray-200 text-sm text-gray-500">
                        Aucune annonce non vérifiée pour le moment.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($annoncesInactives as $annonce)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row gap-4 hover:shadow-md transition relative group">
                                <div class="w-full sm:w-48 h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative">
                                    @if($annonce->photos->isNotEmpty())
                                        <img src="{{ $annonce->photos->first()->lienphoto }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Sans photo</div>
                                    @endif
                                    <span class="absolute top-2 left-2 bg-gray-100 text-gray-800 text-xs font-bold px-2 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Non vérifiée
                                    </span>
                                </div>

                                <div class="flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $annonce->titreannonce }}</h3>
                                            <span class="font-bold text-gray-900 text-lg">{{ $annonce->prixnuitee }} €</span>
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $annonce->adresse->ville->nomville ?? 'Lieu inconnu' }}
                                            <span class="mx-1">•</span>
                                            {{ $annonce->typeHebergement->nomtypehebergement ?? 'Logement' }}
                                        </p>

                                        <div class="mt-3 flex gap-4 text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> 0 vues</span>
                                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> 0 messages</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6 mt-4 pt-3 border-t border-gray-100 text-sm font-bold">
                                        <span class="text-gray-500 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2 2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            En attente de vérification
                                        </span>
                                        <button class="text-gray-700 hover:text-orange-600 flex items-center gap-1 transition group">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Modifier
                                        </button>
                                        <button class="text-gray-700 hover:text-red-600 flex items-center gap-1 transition ml-auto group">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Supprimer
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('#annonces-tabs .tab-btn');
            const panels = {
                'tab-en-ligne': document.getElementById('tab-en-ligne'),
                'tab-inactives': document.getElementById('tab-inactives'),
            };

            const setActive = (target) => {
                buttons.forEach(btn => {
                    const isActive = btn.dataset.target === target;
                    btn.classList.toggle('border-b-2', isActive);
                    btn.classList.toggle('border-orange-600', isActive);
                    btn.classList.toggle('text-orange-600', isActive);
                    btn.classList.toggle('font-bold', isActive);
                    btn.classList.toggle('text-gray-500', !isActive);
                });
                Object.entries(panels).forEach(([key, panel]) => {
                    if (!panel) return;
                    panel.classList.toggle('hidden', key !== target);
                });
            };

            buttons.forEach(btn => {
                btn.addEventListener('click', () => setActive(btn.dataset.target));
            });

            setActive('tab-en-ligne');
        });
    </script>
    @endpush
</x-app-layout>