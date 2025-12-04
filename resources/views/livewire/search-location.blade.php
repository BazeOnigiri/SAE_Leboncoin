<div class="relative w-full">
    <div 
        x-data="{ 
            initAutocomplete() {
                if (typeof google === 'undefined' || !google.maps || !google.maps.places) return;

                const input = document.getElementById('google-search-input');
                
                const options = {
                    types: ['(regions)'], // Cible les villes, départements, régions
                    componentRestrictions: { country: 'fr' },
                    fields: ['address_components', 'geometry', 'icon', 'name']
                };

                const autocomplete = new google.maps.places.Autocomplete(input, options);

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    
                    if (!place.geometry) {
                        @this.setLocation(input.value);
                        return;
                    }

                    @this.setLocation(place.name);
                });
            }
        }"
        x-init="initAutocomplete()"
        wire:ignore 
        class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors w-full"
    >

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="w-5 h-5 text-slate-800 flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
        </svg>

        <input 
            id="google-search-input"
            type="text" 
            class="border-0 outline-0 focus:outline-none focus:ring-0 bg-transparent w-full text-slate-900 placeholder-slate-400"
            wire:model.live.debounce.500ms="search"
            placeholder="Ville, département ou région..."
            autocomplete="off"
        />

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
            stroke="currentColor" class="w-4 h-4 text-slate-400 flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>

    </div>
    
    <style>
        .pac-container {
            border-radius: 0.5rem;
            margin-top: 5px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            font-family: inherit;
            z-index: 9999; 
        }
        .pac-item {
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .pac-item:hover {
            background-color: #f3f4f6;
        }
        .pac-item-query {
            font-size: 14px;
            color: #1f2937;
        }
    </style>
</div>