<div class="h-full flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="text-xl font-bold text-slate-900">Tous les filtres</h2>
        <button onclick="closeFilters()" class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors"
                @click="sidebarOpen = false" 
                class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                <span class=" font-bold">X</span>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6 hide-scrollbar space-y-8">
        <div>
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <span class="text-lg font-medium text-slate-900">Dates</span>
            </div>

            <div class="flex gap-4 mb-6">
                <div class="flex-1">
                    <label class="text-xs text-gray-500 block mb-1">Arrivée</label>
                    <input 
                        type="date" 
                        wire:model="dateArrivee"
                        max="{{ (date('Y') + 1) }}-12-31"
                        min="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none"
                    >
                </div>
                <div class="flex items-center text-sm text-gray-400 pt-5">au</div>
                <div class="flex-1">
                    <label class="text-xs text-gray-500 block mb-1">Départ</label>
                    <input 
                        type="date" 
                        wire:model="dateDepart"
                        max="{{ (date('Y') + 1) }}-12-31"
                        min="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none"
                    >
                </div>
            </div>
        </div>

        <div class="py-6 border-b border-gray-200 border-t" x-data="{ open: @entangle('showTypes') }">
            
            <div @click="open = !open" class="cursor-pointer group">
                <div class="flex items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="pt-1 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-medium text-slate-800 group-hover:underline decoration-1 underline-offset-2">Type d’hébergement</div>
                            <div class="text-sm text-gray-500 mt-1">
                                @if(count($selectedTypes) > 0)
                                    {{ count($selectedTypes) }} sélectionné(s)
                                @else
                                    Appartement, Maison, Villa...
                                @endif
                            </div>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                        class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>

            <div x-show="open" x-collapse class="mt-5 space-y-4 pl-10">
                @if(count($typesHebergement) > 0)
                    @foreach($typesHebergement as $type)
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="checkbox" 
                                value="{{ $type['idtypehebergement'] }}" 
                                wire:model.live="selectedTypes"
                                class="w-5 h-5 border-gray-300 rounded text-orange-600 focus:ring-orange-500"
                            >
                            <span class="ml-4 text-lg text-slate-700 flex-1">{{ $type['nomtypehebergement'] }}</span>
                        </label>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun type trouvé.</p>
                @endif
            </div>
        </div>

        <div class="border-b border-gray-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-6 h-6 rounded-full border border-slate-600 flex items-center justify-center text-slate-600 font-serif text-sm font-bold">€</div>
                <span class="text-lg font-medium text-slate-800">Prix par nuit</span>
            </div>

            <div class="flex items-center gap-4 mb-2">
                <div class="flex-1">
                    <label class="text-slate-800 text-base mb-2 block">Minimum</label>
                    <div class="flex items-center border border-slate-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-slate-900 focus-within:border-slate-900 h-12">
                        <input type="number" wire:model.live="minPrice" class="w-full h-full px-4 outline-none text-slate-900 placeholder-transparent" placeholder="Min">
                        <div class="h-full px-4 border-l border-slate-300 bg-white flex items-center text-slate-900">€</div>
                    </div>
                </div>
                <div class="flex-1">
                    <label class="text-slate-800 text-base mb-2 block">Maximum</label>
                    <div class="flex items-center border border-slate-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-slate-900 focus-within:border-slate-900 h-12">
                        <input type="number" wire:model.live="maxPrice" class="w-full h-full px-4 outline-none text-slate-900 placeholder-transparent" placeholder="Max">
                        <div class="h-full px-4 border-l border-slate-300 bg-white flex items-center text-slate-900">€</div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span class="text-lg font-medium text-slate-900">Chambres</span>
            </div>

            <div class="flex items-center justify-between mt-6">
                <div class="flex items-center gap-4">
                    <button 
                        wire:click="$set('nbChambres', 0)"
                        class="border rounded-lg px-4 py-2 {{ $nbChambres == 0 ? 'bg-[#ea580c] text-white border-[#ea580c]' : 'border-gray-300 hover:border-slate-400' }}">
                        Tout
                    </button>
                    @foreach([1, 2, 3, 4, 5, 6] as $num)
                        <button 
                            wire:click="$set('nbChambres', {{ $num }})"
                            class="border rounded-lg px-4 py-2 {{ $nbChambres == $num ? 'bg-[#ea580c] text-white border-[#ea580c]' : 'border-gray-300 hover:border-slate-400' }}">
                            {{ $num }}{{ $num == 6 ? '+' : '' }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="py-6 border-b border-gray-200 border-t" x-data="{ open: @entangle('showEquipements') }">
            
            <div @click="open = !open" class="cursor-pointer group">
                <div class="flex items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="pt-1 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-medium text-slate-800 group-hover:underline decoration-1 underline-offset-2">Equipements</div>
                            <div class="text-sm text-gray-500 mt-1">
                                @if(count($selectedEquipements) > 0)
                                    {{ count($selectedEquipements) }} sélectionné(s)
                                @else
                                    Wifi gratuit, Télévision...
                                @endif
                            </div>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                        class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>

            <div x-show="open" x-collapse class="mt-5 space-y-4 pl-10">
                @if(count($equipements) > 0)
                    @foreach($equipements as $equipement)
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="checkbox" 
                                value="{{ $equipement['id'] }}" 
                                wire:model.live="selectedEquipements"
                                class="w-5 h-5 border-gray-300 rounded text-orange-600 focus:ring-orange-500"
                            >
                            <span class="ml-4 text-lg text-slate-700 flex-1">{{ $equipement['nom'] }}</span>
                        </label>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun equipement trouvé.</p>
                @endif
            </div>
        </div>

        <div class="" x-data="{ open: @entangle('showExterieur') }">
            
            <div @click="open = !open" class="cursor-pointer group">
                <div class="flex items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="pt-1 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-medium text-slate-800 group-hover:underline decoration-1 underline-offset-2">Extérieur</div>
                            <div class="text-sm text-gray-500 mt-1">
                                @if(count($selectedExterieur) > 0)
                                    {{ count($selectedExterieur) }} sélectionné(s)
                                @else
                                    Piscine, Jardin, Balcon...
                                @endif
                            </div>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                        class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>

            <div x-show="open" x-collapse class="mt-5 space-y-4 pl-10">
                @if(count($exterieurs) > 0)
                    @foreach($exterieurs as $exterieur)
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="checkbox" 
                                value="{{ $exterieur['id'] }}" 
                                wire:model.live="selectedExterieur"
                                class="w-5 h-5 border-gray-300 rounded text-orange-600 focus:ring-orange-500"
                            >
                            <span class="ml-4 text-lg text-slate-700 flex-1">{{ $exterieur['nom'] }}</span>
                        </label>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun extérieur trouvé.</p>
                @endif
            </div>
        </div>

        <div class="py-6 border-b border-gray-200 border-t" x-data="{ open: @entangle('showServices') }">
            
            <div @click="open = !open" class="cursor-pointer group">
                <div class="flex items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="pt-1 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-medium text-slate-800 group-hover:underline decoration-1 underline-offset-2">Services et accessibilité</div>
                            <div class="text-sm text-gray-500 mt-1">
                                @if(count($selectedServices) > 0)
                                    {{ count($selectedServices) }} sélectionné(s)
                                @else
                                    Petit-déjeuner, Parking...
                                @endif
                            </div>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                        class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>

            <div x-show="open" x-collapse class="mt-5 space-y-4 pl-10">
                @if(count($services) > 0)
                    @foreach($services as $service)
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="checkbox" 
                                value="{{ $service['id'] }}" 
                                wire:model.live="selectedServices"
                                class="w-5 h-5 border-gray-300 rounded text-orange-600 focus:ring-orange-500"
                            >
                            <span class="ml-4 text-lg text-slate-700 flex-1">{{ $service['nom'] }}</span>
                        </label>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun serice trouvé.</p>
                @endif
            </div>
        </div>

    </div>


    <div class="p-4 border-t border-gray-200 flex items-center justify-between bg-white">
        <button wire:click="resetFilters" class="text-slate-900 font-medium px-4 py-2 hover:underline decoration-1 underline-offset-4">
            Tout Effacer
        </button>

        <button 
            wire:click="applyFilters"
            @click="sidebarOpen = false"
            class="bg-[#ea580c] hover:bg-[#c2410c] text-white transition-colors font-bold py-3 px-6 rounded-xl">
            Rechercher @if(count($selectedTypes) > 0) ({{ count($selectedTypes) }}) @endif
        </button>
    </div>
</div>