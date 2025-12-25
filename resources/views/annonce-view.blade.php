@extends('layouts.app')
@section('content')
    <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-32">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <div class="lg:col-span-2 space-y-8">

                <div class="relative w-full h-96 mr-4">
                    <button onclick="scrollLeft{{ $annonce->idannonce }}()"
                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10 hover:bg-black/60 transition">
                        ‹
                    </button>
                    <button onclick="scrollRight{{ $annonce->idannonce }}()"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10 hover:bg-black/60 transition">
                        ›
                    </button>
                    <div id="carousel{{ $annonce->idannonce }}"
                        class="w-full h-full overflow-x-auto flex gap-2 rounded-3xl scroll-smooth snap-x snap-mandatory scrollbar-hide">
                        @foreach ($annonce->photos ?? [] as $photo)
                            <div class="min-w-full h-full snap-start rounded-3xl overflow-hidden">
                                <img src="{{ $photo->lienphoto }}" loading="lazy" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                    <div id="dots{{ $annonce->idannonce }}" class="absolute bottom-4 w-full flex justify-center gap-2">
                        @foreach ($annonce->photos ?? [] as $i => $photo)
                            <div class="dot{{ $annonce->idannonce }} w-2 h-2 rounded-full bg-white/50 transition-all duration-300">
                            </div>
                        @endforeach
                    </div>
                </div>

                <script>
                    const carousel{{ $annonce->idannonce }} = document.getElementById("carousel{{ $annonce->idannonce }}");
                    const dots{{ $annonce->idannonce }} = document.querySelectorAll(".dot{{ $annonce->idannonce }}");
                    const total{{ $annonce->idannonce }} = dots{{ $annonce->idannonce }}.length;

                    function scrollRight{{ $annonce->idannonce }}() {
                        let el = carousel{{ $annonce->idannonce }};
                        if (el.scrollLeft + el.clientWidth >= el.scrollWidth - 5) {
                            el.scrollTo({ left: 0, behavior: "smooth" });
                        } else {
                            el.scrollBy({ left: el.clientWidth, behavior: "smooth" });
                        }
                    }

                    function scrollLeft{{ $annonce->idannonce }}() {
                        let el = carousel{{ $annonce->idannonce }};
                        if (el.scrollLeft <= 5) {
                            el.scrollTo({ left: el.scrollWidth, behavior: "smooth" });
                        } else {
                            el.scrollBy({ left: -el.clientWidth, behavior: "smooth" });
                        }
                    }
                    carousel{{ $annonce->idannonce }}.addEventListener("scroll", () => {
                        let el = carousel{{ $annonce->idannonce }};
                        let index = Math.round(el.scrollLeft / el.clientWidth);

                        dots{{ $annonce->idannonce }}.forEach((dot, i) => {
                            if (i === index) {
                                dot.classList.add("w-3", "h-3", "bg-white");
                                dot.classList.remove("bg-white/50", "w-2", "h-2");
                            } else {
                                dot.classList.remove("w-3", "h-3", "bg-white");
                                dot.classList.add("bg-white/50", "w-2", "h-2");
                            }
                        });
                    });
                    if(dots{{ $annonce->idannonce }}.length > 0) {
                        dots{{ $annonce->idannonce }}[0].classList.add("w-3", "h-3", "bg-white");
                    }
                </script>
                <script>
                    function updateReservationLink() {
                        const arriveeInput = document.getElementById('dateArriveeInput');
                        const departInput = document.getElementById('dateDepartInput');
                        const btn = document.getElementById('btnReserver');
                        const warning = document.getElementById('dateWarning');

                        if (arriveeInput.value) {
                            departInput.min = arriveeInput.value;
                            if (departInput.value && departInput.value < arriveeInput.value) {
                                departInput.value = arriveeInput.value;
                            }
                        }

                        if (arriveeInput.value && departInput.value) {

                            let baseUrl = "";
                            @auth
                                baseUrl = "{{ route('reservation.create', ['id' => $annonce->idannonce]) }}";
                            @else
                                baseUrl = "{{ route('check.reservation', ['id' => $annonce->idannonce]) }}";
                            @endauth

                            btn.href = baseUrl + "?arrivee=" + arriveeInput.value + "&depart=" + departInput.value;

                            btn.classList.remove('bg-gray-300', 'cursor-not-allowed', 'pointer-events-none', 'shadow-none');
                            btn.classList.add('bg-[#EA580C]', 'hover:bg-[#C2410C]', 'shadow-sm', 'cursor-pointer');

                            warning.style.display = 'none';
                        
                        } else {

                            btn.removeAttribute('href');

                            btn.classList.add('bg-gray-300', 'cursor-not-allowed', 'pointer-events-none', 'shadow-none');
                            btn.classList.remove('bg-[#EA580C]', 'hover:bg-[#C2410C]', 'shadow-sm', 'cursor-pointer');

                            warning.style.display = 'block';
                        }
                    }
                </script>
                <script>
                    function updateDepartMin() {
                        const dateArrivee = document.getElementById('dateArrivee');
                        const dateDepart = document.getElementById('dateDepart');
                        
                        if (dateArrivee.value) {
                            dateDepart.min = dateArrivee.value;
                            if (dateDepart.value && dateDepart.value < dateArrivee.value) {
                                dateDepart.value = dateArrivee.value;
                            }
                        }
                    }
                </script>
                <div>
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <h1 class="text-3xl font-bold text-slate-900">{{ $annonce->titreannonce }}</h1>
                        
                        <div class="flex items-center gap-2">
                            <div x-data="{ 
                                open: false,
                                url: window.location.href,
                                title: '{{ addslashes($annonce->titreannonce) }}',
                                copyToClipboard() {
                                    navigator.clipboard.writeText(this.url).then(() => {
                                        alert('Lien copié !');
                                    });
                                },
                                share(network) {
                                    let shareUrl = '';
                                    switch(network) {
                                        case 'facebook':
                                            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.url)}`;
                                            break;
                                        case 'twitter':
                                            shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(this.title)}&url=${encodeURIComponent(this.url)}`;
                                            break;
                                        case 'whatsapp':
                                            shareUrl = `https://wa.me/?text=${encodeURIComponent(this.title + ' ' + this.url)}`;
                                            break;
                                        case 'instagram': 
                                            shareUrl = 'https://www.instagram.com/';
                                            break;
                                    }
                                    if(shareUrl) window.open(shareUrl, '_blank');
                                }
                            }" class="relative">
                                <button @click="open = !open" @click.outside="open = false" 
                                    class="bg-gray-100 p-3 rounded-full hover:bg-gray-200 transition flex items-center justify-center w-12 h-12 text-gray-500 hover:text-slate-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                                    </svg>
                                </button>

                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden" 
                                    style="display: none;">
                                    <div class="py-1">
                                        <button @click="share('facebook')" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            Facebook
                                        </button>
                                        <button @click="share('twitter')" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                            X (Twitter)
                                        </button>
                                        <button @click="share('instagram')" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                            Instagram
                                        </button>
                                        <button @click="share('whatsapp')" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.463 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                            WhatsApp
                                        </button>
                                        <hr class="border-gray-100 my-1">
                                        <button @click="copyToClipboard()" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5" />
                                            </svg>
                                            Copier le lien
                                        </button>
                                    </div>
                                </div>
                            </div>

                        @auth
                        <div x-data="{ 
                            isFavorite: {{ $isFavorite ? 'true' : 'false' }},
                            loading: false
                        }">
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
                            " class="bg-gray-100 p-3 rounded-full hover:bg-gray-200 transition relative flex items-center justify-center w-12 h-12 flex-shrink-0 group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                    class="w-6 h-6 transition-colors duration-300"
                                    :class="isFavorite ? 'text-red-500 fill-red-500' : 'text-gray-400 group-hover:text-red-500'">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                                <span x-show="loading" class="absolute inset-0 flex items-center justify-center bg-gray-100 rounded-full" style="display: none;">
                                    <svg class="animate-spin h-5 w-5 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                        @else
                        <div x-data="{ 
                            isFavorite: JSON.parse(localStorage.getItem('guest_favorites') || '[]').includes({{ $annonce->idannonce }}),
                            toggle() {
                                let favorites = JSON.parse(localStorage.getItem('guest_favorites') || '[]');
                                const id = {{ $annonce->idannonce }};
                                
                                if (favorites.includes(id)) {
                                    favorites = favorites.filter(f => f !== id);
                                    this.isFavorite = false;
                                } else {
                                    favorites.push(id);
                                    this.isFavorite = true;
                                }
                                
                                localStorage.setItem('guest_favorites', JSON.stringify(favorites));
                            },
                            init() {
                                let favorites = JSON.parse(localStorage.getItem('guest_favorites') || '[]');
                                this.isFavorite = favorites.includes({{ $annonce->idannonce }});
                            }
                        }">
                            <button @click="toggle()" class="bg-gray-100 p-3 rounded-full hover:bg-gray-200 transition relative flex items-center justify-center w-12 h-12 flex-shrink-0 group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                    class="w-6 h-6 transition-colors duration-300"
                                    :class="isFavorite ? 'text-red-500 fill-red-500' : 'text-gray-400 group-hover:text-red-500'">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                        </div>
                        @endauth
                        </div>
                    </div>

                    <div class="text-sm mb-3 text-slate-600 flex flex-wrap items-center gap-2">
                        <span>{{ $annonce->capacite ?? '?' }} pers.</span>
                        <span class="font-bold">·</span>
                        <span>{{ $annonce->nbchambres ?? '?' }} chambres</span>
                        <span class="font-bold">·</span>
                        <span>{{ $annonce->adresse->ville->nomville ?? 'Ville inconnue' }}</span>
                        <span class="font-bold">·</span>
                        <div class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4 text-[#b84a14] fill-current" viewBox="0 0 24 24">
                                <path d="M11.0437 2.29647C10.7942 2.46286 10.5447 2.71245 10.3784 3.04524L8.29938 7.6211L3.55925 8.28668C3.22661 8.28668 2.97713 8.45307 2.64449 8.61947C2.39501 8.86906 2.14553 9.11865 2.06237 9.45144C1.97921 9.86742 1.97921 10.2002 2.06237 10.533C2.14553 10.8658 2.31185 11.1986 2.56133 11.4482L6.05405 14.9425L5.22245 19.9343C5.13929 20.2671 5.22245 20.5999 5.30561 20.9327C5.38877 21.2655 5.63825 21.5151 5.88773 21.6814C6.13721 21.8478 6.46985 22.0142 6.8025 22.0142C7.13514 22.0142 7.46778 21.931 7.71726 21.8478L12.0416 19.5183L16.3659 21.8478C16.6154 22.0142 16.948 22.0974 17.2807 22.0142C17.6133 22.0142 17.9459 21.8478 18.1954 21.6814C18.4449 21.5151 18.6944 21.1823 18.7775 20.9327C18.8607 20.5999 18.9439 20.2671 18.8607 19.9343L18.0291 14.9425L21.4387 11.365C21.6882 11.1154 21.8545 10.8658 21.9376 10.533C22.0208 10.2002 22.0208 9.86742 21.9376 9.53464C21.8545 9.20185 21.6882 8.95225 21.4387 8.70266C21.1892 8.45307 20.8566 8.36987 20.6071 8.28668L15.8669 7.5379L13.7048 3.04524C13.5385 2.71245 13.3721 2.46286 13.0395 2.29647C12.79 2.13007 12.5405 2.04688 12.2911 2.04688H11.9584C11.5426 2.13007 11.2931 2.21327 11.0437 2.29647Z"></path>
                            </svg>
                            <span class="opacity-100 font-semibold">{{ number_format($annonce->avis_avg_nombreetoiles, 1) }}</span>
                            <span class="opacity-50">({{ $annonce->avis_count }} avis)</span>
                        </div>
                    </div>

                    <div class="text-sm text-slate-500">
                        <p>Publié le <span class="font-bold">{{ $annonce->datePublication ? \Carbon\Carbon::parse($annonce->datePublication->date)->format('d/m/Y') : 'Date inconnue' }}</span></p>
                    </div>
                </div>

                <hr class="border-gray-200">

                <section>
                    <h2 class="text-xl font-black mb-6 text-slate-800">Critères</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Classement</span>
                                <span class="font-bold text-sm text-neutral-900">{{ $annonce->nombreetoilesleboncoin ? $annonce->nombreetoilesleboncoin . ' étoiles' : 'Non classé' }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Capacité</span>
                                <span class="font-bold text-sm text-neutral-900">{{ $annonce->capacite ?? '?' }} personnes</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Type</span>
                                <span class="font-bold text-sm text-neutral-900">{{ $annonce->typehebergement->nomtypehebergement ?? 'Non spécifié' }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Chambres</span>
                                <span class="font-bold text-sm text-neutral-900">{{ $annonce->nbchambres ?? 0 }} chambres</span>
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-200">

                <section>
                    <h2 class="text-xl font-black mb-6 text-slate-800">Conditions</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Arrivée</span>
                                <span class="font-bold text-sm text-neutral-900">{{ $annonce->heurearrivee->heure ?? 'Non spécifiée' }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Départ</span>
                                <span class="font-bold text-sm text-neutral-900">{{ $annonce->heuredepart->heure ?? 'Non spécifiée' }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Animaux</span>
                                <span class="font-bold text-sm text-neutral-900">Non</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 mb-0.5">Fumeur</span>
                                <span class="font-bold text-sm text-neutral-900">{{ $annonce->possibilitefumeur ? 'Autorisé' : 'Non-fumeur' }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-200">

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Équipements et services</h2>
                    @if ($commoditesGroupees->isNotEmpty())
                        <div>
                            @foreach ($commoditesGroupees as $type => $commodites)
                                @unless ($loop->first)
                                    <hr class="border-t border-gray-100 my-6">
                                @endunless
                                <div>
                                    <h3 class="font-bold text-base text-slate-800 mb-4">{{ $type }}</h3>
                                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-y-2">
                                        @foreach ($commodites as $commodite)
                                            <li class="text-slate-600 font-medium flex items-center gap-2">
                                                <span>•</span> {{ $commodite->nomcommodite }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <hr class="border-gray-200">

                <p>Publié par
                    <a class="underline font-bold" href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}">
                        {{ $annonce->utilisateur->pseudonyme ?? 'Utilisateur inconnu' }}
                    </a>
                </p>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24"> 
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-6">

                        @php
                            $price = number_format($annonce->prixnuitee, 2, '.', '');
                            $price = preg_replace('/\.00$/', '', $price);
                        @endphp
                        <div class="mb-4">
                            <span class="text-2xl font-bold text-slate-900">{{ $price }} €</span> <span class="text-slate-500">par nuit</span>
                        </div>





                        <p class="text-sm font-bold text-slate-800 mb-2">Sélectionnez vos dates de séjour :</p>

                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Arrivée</label>
                                    <div class="relative">
                                        <input type="date" 
                                            id="dateArriveeInput"
                                            min="{{ date('Y-m-d') }}" 
                                            onchange="updateReservationLink()"
                                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Départ</label>
                                    <div class="relative">
                                        <input type="date" 
                                            id="dateDepartInput"
                                            min="{{ date('Y-m-d') }}" 
                                            onchange="updateReservationLink()"
                                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                                    </div>
                                </div>
                            </div>

                            <p id="dateWarning" class="text-xs text-red-500 font-medium mb-4 text-center">
                                * Veuillez sélectionner vos dates pour continuer
                            </p>

                            <div class="bg-[#FEF9C3] text-[#854D0E] text-xs font-bold px-3 py-1.5 rounded-lg mb-6 w-fit inline-block">
                                Disponibilités non confirmées
                            </div>

                            @auth
                                <a id="btnReserver" 
                                    href="#" 
                                    class="block text-center w-full bg-gray-300 text-white font-bold text-lg py-3 rounded-xl shadow-none cursor-not-allowed pointer-events-none transition mb-6">
                                    Réserver
                                </a>
                            @else
                                <a id="btnReserver" 
                                    href="#" 
                                    class="block text-center w-full bg-gray-300 text-white font-bold text-lg py-3 rounded-xl shadow-none cursor-not-allowed pointer-events-none transition mb-6">
                                    Réserver
                                </a>
                            @endauth






                        <hr class="border-gray-100 mb-6">

                        <div class="flex items-center justify-between">
                            <a href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}" class="flex items-center gap-3 group">
                                <div class="w-12 h-12 rounded-full bg-[#C2410C] text-white flex items-center justify-center text-xl font-normal">
                                    {{ strtoupper(substr($annonce->utilisateur->pseudonyme ?? 'U', 0, 1)) }}
                                </div>
                                
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900 group-hover:underline decoration-2">
                                        {{ $annonce->utilisateur->pseudonyme ?? 'Utilisateur' }}
                                    </span>
                                    <span class="text-sm text-slate-500">
                                        {{ $annonce->utilisateur->annonces_count ?? 0 }} annonce(s)
                                    </span>
                                </div>
                            </a>
                            
                            <a href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}" class="text-slate-400 hover:text-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div> 


        <hr class="my-12 opacity-50">

        <div class="my-8">
            <div class="flex justify-between items-center mb-6 px-1">
                <h2 class="text-2xl font-black text-slate-800">Ces annonces peuvent vous intéresser</h2>
                <a href="#" class="text-slate-800 font-semibold hover:underline flex items-center">
                    Voir plus d'annonces <span class="ml-1">→</span>
                </a>
            </div>

            <div class="flex overflow-x-auto space-x-6 pb-6 px-1 snap-x scrollbar-hide">
                @foreach ($annonce->annonces as $annonce)
                    <a href="{{ route('annonce.view', ['id' => $annonce->idannonce]) }}"
                        class="snap-start flex-shrink-0 w-72 block bg-white border border-gray-100 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                        <div class="relative">
                            <img src="{{ $annonce->photos->first()->lienphoto ?? 'https://via.placeholder.com/300' }}"
                                alt="{{ $annonce->titreannonce }}" class="w-full h-52 object-cover rounded-t-2xl">
                            <button class="absolute top-3 right-3 bg-white p-2 rounded-full shadow-sm hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-4 flex flex-col gap-1">
                            <p class="font-bold text-lg truncate text-slate-800">{{ $annonce->titreannonce }}</p>
                            <p class="text-slate-600 text-sm">
                                @php
                                    $price = number_format($annonce->prixnuitee, 2, '.', '');
                                    $price = preg_replace('/\.00$/', '', $price);
                                @endphp
                                à partir de <span class="font-black text-slate-900">{{ $price }} €</span> / nuit
                            </p>
                            <div class="mt-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                                    Paiement en ligne
                                </span>
                            </div>
                            <p class="text-gray-500 text-sm mt-2">
                                {{ $annonce->adresse->ville->nomville }} {{ $annonce->adresse->ville->codepostal }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
@endsection