<div class="h-full flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="text-xl font-bold text-slate-900">Tous les filtres</h2>
        <button onclick="closeFilters()" class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors"
                @click="sidebarOpen = false" 
                class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                <span>X</span>
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
                    <input type="text" placeholder="JJ/MM/AAAA" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                </div>
                <div class="flex items-center text-sm text-gray-400 pt-5">au</div>
                <div class="flex-1">
                    <label class="text-xs text-gray-500 block mb-1">Départ</label>
                    <input type="text" placeholder="JJ/MM/AAAA" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                </div>
            </div>
            <hr class="border-gray-200 mt-2">
        </div>
        <div>
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span class="text-lg font-medium text-slate-900">Voyageurs</span>
            </div>
            
            <div class="flex items-center justify-between">
                <span class="text-slate-900">Nombre de voyageurs</span>
                <div class="flex items-center gap-4">
                    <button class="w-8 h-8 rounded-full border border-gray-400 text-gray-400 flex items-center justify-center hover:border-gray-600 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </button>
                    <span class="w-4 text-center text-slate-900 font-medium">1</span>
                    <button class="w-8 h-8 rounded-full border border-slate-900 text-slate-900 flex items-center justify-center hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>
            </div>
            <hr class="border-gray-200 mt-6">
        </div>

        <div>
            <div class="flex items-center justify-between pb-6">
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                    </label>
                    <span class="text-lg font-medium text-slate-900">Paiement en ligne</span>
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

        <div class="py-6 border-b border-gray-200">
            <div class="flex items-center gap-4 mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
                    </svg>
                <span class="text-lg font-medium text-slate-800">Nature du logement</span>
            </div>

            <div class="space-y-5">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" class="custom-checkbox">
                    <span class="ml-4 text-lg text-slate-700 flex-1">Location ou Gîte</span>
                    <span class="text-sm text-gray-400 tabular-nums">227 007</span>
                </label>
            </div>
        </div>

        <div class="py-6 border-b border-gray-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-6 h-6 rounded-full border border-slate-600 flex items-center justify-center text-slate-600 font-serif italic text-sm font-bold">€</div>
                <span class="text-lg font-medium text-slate-800">Prix par nuit</span>
            </div>

            <div class="flex items-center gap-4 mb-2">
                <div class="flex-1">
                    <label class="text-slate-800 text-base mb-2 block">Minimum</label>
                    <div class="flex items-center border border-slate-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-slate-900 focus-within:border-slate-900 h-12">
                        <input type="number" class="w-full h-full px-4 outline-none text-slate-900 placeholder-transparent" placeholder="Min">
                        <div class="h-full px-4 border-l border-slate-300 bg-white flex items-center text-slate-900">€</div>
                    </div>
                </div>
                <div class="flex-1">
                    <label class="text-slate-800 text-base mb-2 block">Maximum</label>
                    <div class="flex items-center border border-slate-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-slate-900 focus-within:border-slate-900 h-12">
                        <input type="number" class="w-full h-full px-4 outline-none text-slate-900 placeholder-transparent" placeholder="Max">
                        <div class="h-full px-4 border-l border-slate-300 bg-white flex items-center text-slate-900">€</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="p-4 border-t border-gray-200 flex items-center justify-between bg-white">
        <button wire:click="$set('selectedTypes', [])" class="text-slate-900 font-medium px-4 py-2 hover:underline decoration-1 underline-offset-4">
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