<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <aside class="lg:col-span-1 space-y-8 bg-white p-6 rounded-xl border border-gray-200 h-fit">
        
        <div>
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                🏠 Types d'hébergement
            </h3>
            <div class="space-y-2">
                @foreach($typesHebergement as $type)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" value="{{ $type->idtypehebergement }}" 
                               wire:model.live="selectedTypes"
                               class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        <span class="text-sm text-slate-700 group-hover:text-orange-600 transition-colors">
                            {{ $type->nomtypehebergement }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                🔌 Équipements
            </h3>
            <div class="space-y-2">
                @foreach($equipements as $equip)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" value="{{ $equip->idcommodite }}" 
                               wire:model.live="selectedEquipements"
                               class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        <span class="text-sm text-slate-700 group-hover:text-orange-600 transition-colors">
                            {{ $equip->nomcommodite }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <button wire:click="$set('selectedTypes', []); $set('selectedEquipements', [])" 
                class="w-full text-xs text-gray-500 hover:text-orange-600 underline text-center mt-4">
            Réinitialiser les filtres
        </button>
    </aside>

    <section class="lg:col-span-3">
        <div class="grid gap-6">
            @forelse($annonces as $annonce)
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow flex gap-6">

                    <div class="w-28 h-28 bg-slate-50 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200">
                        @if($annonce->photos && $annonce->photos->isNotEmpty())
                            @php 
                                $url = $annonce->photos->first()->lienphoto;
                                $isExternal = str_starts_with($url, 'http');
                                $src = $isExternal ? $url : asset('storage/' . ltrim($url, '/'));
                            @endphp
                            
                            <img src="{{ $src }}" 
                                alt="Photo {{ $annonce->titreannonce }}" 
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-xl text-slate-900">{{ $annonce->titreannonce }}</h4>
                                <span class="text-xs font-black uppercase tracking-widest text-slate-400 bg-slate-100 px-2 py-1 rounded">
                                    ID: #{{ $annonce->idannonce }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 mt-2">
                                <div class="w-7 h-7 bg-orange-50 rounded-full flex items-center justify-center text-orange-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <p class="text-sm">
                                    <span class="text-slate-500">Propriétaire :</span>
                                    <span class="font-bold text-slate-800">{{ $annonce->utilisateur->pseudonyme ?? 'Anonyme' }}</span>
                                </p>
                            </div>
                            
                            <p class="text-sm text-orange-600 font-medium mt-1">
                                {{ $annonce->typehebergement->nomtypehebergement ?? 'Type non défini' }}
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-end">
                            <a href="{{ route('services.annonce-editor.edit', $annonce->idannonce) }}" class="flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                Éditer l'annonce
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center bg-white rounded-2xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-500 font-medium">Aucune annonce ne correspond à votre sélection.</p>
                </div>
            @endforelse
            
            <div class="mt-8">
                {{ $annonces->links() }}
            </div>
        </div>
    </section>
</div>