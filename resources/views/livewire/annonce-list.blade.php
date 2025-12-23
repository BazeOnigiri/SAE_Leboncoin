<div>
    @if (session('success'))
    <div x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 8000)" 
        x-show="show" 
        x-transition.duration.500ms
        class="bottom-0 right-0 mb-6 mr-6 fixed z-50">
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 border rounded-md shadow-lg relative overflow-hidden">
            {{ session('success') }}
            <div class="mt-2 h-1 bg-green-200 overflow-hidden rounded">
                <div class="h-full bg-green-500 transition-all duration-[8000ms] ease-linear w-0"
                    x-init="setTimeout(() => $el.style.width = '100%', 50)"></div>
            </div>
        </div>
    </div>
    @endif
    @php
        $hasSearch = !empty($location) 
            || !empty($filterTypes) 
            || (!empty($dateArrivee) && !empty($dateDepart))
            || ($nbVoyageurs > 1)
            || ($nbChambres > 0)
            || !empty($selectedCommodites)
            || !empty($minPrice)
            || !empty($maxPrice);
    @endphp

    <h1 class="text-xl font-bold mb-6">Annonces Location vacances</h1>
    
    <p class="mb-6 font-bold">{{ $annonces->total() }} annonces {{ $location ? 'pour "' . $location . '"' : '' }}</p>

    <div class="flex flex-col lg:flex-row gap-6 relative">

        <div class="w-full {{ $hasSearch ? 'lg:w-2/3' : '' }}">
            <button wire:click="saveSearch" class="bg-[#ea580c] hover:bg-[#c2410c] text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                Sauvegarder la recherche
            </button>

            @if($annonces->isEmpty())
                <div class="p-10 text-center text-gray-500">
                    Aucune annonce trouvée pour cette recherche.
                </div>
            @else
                @foreach ($annonces as $annonce)
                    <div class="flex mb-2.5">
                        <div class="relative w-80 h-56 mr-4 flex-shrink-0">
                            <button onclick="scrollLeft{{ $annonce->idannonce }}()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10">‹</button>
                            <button onclick="scrollRight{{ $annonce->idannonce }}()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10">›</button>
                        
                            <div id="carousel{{ $annonce->idannonce }}" class="w-full h-full overflow-x-auto flex gap-2 rounded-3xl scroll-smooth snap-x snap-mandatory scroll r-hide">
                                @foreach ($annonce->photos ?? [] as $photo)
                                    <div class="min-w-full h-full snap-start rounded-3xl overflow-hidden">
                                        <img src="{{ $photo->lienphoto }}" loading="lazy" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        
                            <div id="dots{{ $annonce->idannonce }}" class="absolute bottom-1 w-full flex justify-center gap-2">
                                @foreach ($annonce->photos ?? [] as $i => $photo)
                                    <div class="dot{{ $annonce->idannonce }} w-2 h-2 rounded-full bg-white/50 transition-all duration-300"></div>
                                @endforeach
                            </div>
                            
                            @auth
                            <div x-data="{ 
                                isFavorite: {{ in_array($annonce->idannonce, $favoriteIds ?? []) ? 'true' : 'false' }},
                                loading: false
                            }" class="absolute top-2 right-2 z-20">
                                <button @click="
                                    loading = true;
                                    isFavorite = !isFavorite;
                                    fetch('{{ route('user.favorites.toggle') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ idannonce: {{ $annonce->idannonce }} })
                                    }).then(() => loading = false).catch(() => { isFavorite = !isFavorite; loading = false; })
                                " class="bg-white/80 backdrop-blur-sm p-2 rounded-full shadow hover:bg-white transition relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                                        class="w-5 h-5 transition-colors duration-300"
                                        :class="isFavorite ? 'text-red-500 fill-red-500' : 'text-gray-600'">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                    <span x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-full">
                                        <svg class="animate-spin h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            @else
                            <div class="absolute top-2 right-2 z-20">
                                <a href="{{ route('auth.check') }}" class="block bg-white/80 backdrop-blur-sm p-2 rounded-full shadow hover:bg-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </a>
                            </div>
                            @endauth
                        </div>

                        <script>
                            try {
                                const carousel{{ $annonce->idannonce }} = document.getElementById("carousel{{ $annonce->idannonce }}");
                                const dots{{ $annonce->idannonce }} = document.querySelectorAll(".dot{{ $annonce->idannonce }}");
                                
                                window.scrollRight{{ $annonce->idannonce }} = function() {
                                    let el = carousel{{ $annonce->idannonce }};
                                    if (el.scrollLeft + el.clientWidth >= el.scrollWidth - 5) el.scrollTo({ left: 0, behavior: "smooth" });
                                    else el.scrollBy({ left: el.clientWidth, behavior: "smooth" });
                                }
                                window.scrollLeft{{ $annonce->idannonce }} = function() {
                                    let el = carousel{{ $annonce->idannonce }};
                                    if (el.scrollLeft <= 5) el.scrollTo({ left: el.scrollWidth, behavior: "smooth" });
                                    else el.scrollBy({ left: -el.clientWidth, behavior: "smooth" });
                                }
                                carousel{{ $annonce->idannonce }}.addEventListener("scroll", () => {
                                    let el = carousel{{ $annonce->idannonce }};
                                    let index = Math.round(el.scrollLeft / el.clientWidth);
                                    dots{{ $annonce->idannonce }}.forEach((dot, i) => {
                                        if (i === index) { dot.classList.add("w-3", "h-3", "bg-white"); dot.classList.remove("bg-white/50", "w-2", "h-2"); }
                                        else { dot.classList.remove("w-3", "h-3", "bg-white"); dot.classList.add("bg-white/50", "w-2", "h-2"); }
                                    });
                                });
                                if(dots{{ $annonce->idannonce }}.length > 0) dots{{ $annonce->idannonce }}[0].classList.add("w-3", "h-3", "bg-white");
                            } catch(e){}
                        </script>

                        <a href="{{ route('annonce.view', ['id' => $annonce->idannonce]) }}" class="flex h-auto flex-col justify-between w-full">
                            <div>
                                <div class="gap-x-sm flex items-center justify-between w-80">
                                    <h2 class=" font-black">{{ $annonce->titreannonce }}</h2>
                                    <div class="flex">
                                        <svg class=" mr-0.5" style="fill: #b84a14; height: 18px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M11.0437 2.29647C10.7942 2.46286 10.5447 2.71245 10.3784 3.04524L8.29938 7.6211L3.55925 8.28668C3.22661 8.28668 2.97713 8.45307 2.64449 8.61947C2.39501 8.86906 2.14553 9.11865 2.06237 9.45144C1.97921 9.86742 1.97921 10.2002 2.06237 10.533C2.14553 10.8658 2.31185 11.1986 2.56133 11.4482L6.05405 14.9425L5.22245 19.9343C5.13929 20.2671 5.22245 20.5999 5.30561 20.9327C5.38877 21.2655 5.63825 21.5151 5.88773 21.6814C6.13721 21.8478 6.46985 22.0142 6.8025 22.0142C7.13514 22.0142 7.46778 21.931 7.71726 21.8478L12.0416 19.5183L16.3659 21.8478C16.6154 22.0142 16.948 22.0974 17.2807 22.0142C17.6133 22.0142 17.9459 21.8478 18.1954 21.6814C18.4449 21.5151 18.6944 21.1823 18.7775 20.9327C18.8607 20.5999 18.9439 20.2671 18.8607 19.9343L18.0291 14.9425L21.4387 11.365C21.6882 11.1154 21.8545 10.8658 21.9376 10.533C22.0208 10.2002 22.0208 9.86742 21.9376 9.53464C21.8545 9.20185 21.6882 8.95225 21.4387 8.70266C21.1892 8.45307 20.8566 8.36987 20.6071 8.28668L15.8669 7.5379L13.7048 3.04524C13.5385 2.71245 13.3721 2.46286 13.0395 2.29647C12.79 2.13007 12.5405 2.04688 12.2911 2.04688H11.9584C11.5426 2.13007 11.2931 2.21327 11.0437 2.29647Z"></path></svg>
                                        <span class=" text-sm opacity-75">{{ $annonce->nombreetoilesleboncoin }}</span>
                                    </div>
                                </div>
                                <p>{{ $annonce->nombreetoilesleboncoin }} / 5</p>
                                <div class=" text-sm">
                                    {{ $annonce->capacite ?? '?' }} pers.
                                    <span class="mx-sm text-neutral inline-block font-bold">·</span>
                                    {{ $annonce->typehebergement->nomtypehebergement }}
                                </div>
                            </div>
                            <div>
                                <p class=" font-bold text-sm">
                                    @php $price = preg_replace('/\.00$/', '', number_format($annonce->prixnuitee, 2, '.', '')); @endphp
                                    <small>à partir de </small>{{ $price }} € <small> / nuit</small>
                                </p>
                                <p class=" text-xs opacity-75">{{ $annonce->adresse->ville->nomville }} {{ $annonce->adresse->ville->codepostal }}</p>
                            </div>
                        </a>
                    </div>
                    <hr class="my-6 opacity-50">
                @endforeach
            @endif

            <div class="mt-6 flex justify-center">
                {{ $annonces->links('pagination.custom') }}
            </div>
        </div>

        @if($hasSearch)
            <div class="hidden lg:block lg:w-1/3 relative">
                <div class="sticky top-24 h-[600px] w-full rounded-xl overflow-hidden shadow-lg border border-gray-200" wire:ignore>
                    <div id="map" style="height: 100%; width: 100%;"></div>
                </div>
            </div>
        @endif

    </div>

    @script
    <script>
        let map;
        let markersLayer;

        function initMap() {
            const mapElement = document.getElementById('map');
            
            if (!mapElement) {
                if (map) { 
                    map.remove(); 
                    map = null; 
                }
                return;
            }

            if (map && document.body.contains(map.getContainer())) return;

            map = L.map('map').setView([46.603354, 1.888334], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
            markersLayer = L.layerGroup().addTo(map);
        }

        function updateMarkers(markersData) {
            initMap();

            if (!map) return;

            markersLayer.clearLayers();

            if (!markersData || markersData.length === 0) return;

            let bounds = L.latLngBounds();

            let groups = {};
            markersData.forEach(marker => {
                let key = marker.lat + '_' + marker.lng;
                if (!groups[key]) groups[key] = [];
                groups[key].push(marker);
            });

            Object.values(groups).forEach(group => {
                let count = group.length;
                
                group.forEach((marker, index) => {
                    let lat = parseFloat(marker.lat);
                    let lng = parseFloat(marker.lng);

                    if (count > 1) {
                        let angle = (index / count) * Math.PI * 2; 
                        let radius = 0.0001; 
                        
                        lat += Math.sin(angle) * radius;
                        lng += Math.cos(angle) * radius;
                    }

                    let popupContent = `
                        <div class="text-center min-w-[150px]">
                            ${marker.img ? `<img src="${marker.img}" class="w-full h-24 object-cover rounded mb-2">` : ''}
                            <b class="text-sm block mb-1">${marker.title}</b>
                            <span class="font-bold text-white bg-orange-600 px-2 py-1 rounded text-xs">${marker.price} €</span>
                        </div>
                    `;

                    let m = L.marker([lat, lng]).bindPopup(popupContent);
                    markersLayer.addLayer(m);
                    bounds.extend([lat, lng]);
                });
            });

            if (markersData.length > 0) map.fitBounds(bounds, { padding: [50, 50], maxZoom: 12 });
        }

        initMap();
        updateMarkers($wire.markers);

        $wire.on('update-map', () => {
            updateMarkers($wire.markers);
        });
    </script>
    @endscript
</div>