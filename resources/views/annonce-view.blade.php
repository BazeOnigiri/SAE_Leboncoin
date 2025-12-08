@extends('layouts.app')
@section('content')
    <div class="bg-white p-7 max-w-7xl mx-auto px-4 md:px-8 xl:px-12 pt-32">
        <div class="relative w-full h-[400px] md:h-[500px] mb-8">
            <button onclick="scrollLeft{{ $annonce->idannonce }}()"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-black rounded-full w-10 h-10 flex items-center justify-center z-10 shadow-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button onclick="scrollRight{{ $annonce->idannonce }}()"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-black rounded-full w-10 h-10 flex items-center justify-center z-10 shadow-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <div id="carousel{{ $annonce->idannonce }}"
                class="w-full h-full overflow-x-auto flex gap-0 rounded-2xl scroll-smooth snap-x snap-mandatory scrollbar-hide">
                @foreach ($annonce->photos ?? [] as $photo)
                    <div class="min-w-full h-full snap-center relative">
                        <img src="{{ $photo->lienphoto }}" loading="lazy" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
            
            <div id="dots{{ $annonce->idannonce }}" class="absolute bottom-4 w-full flex justify-center gap-2">
                @foreach ($annonce->photos ?? [] as $i => $photo)
                    <div class="dot{{ $annonce->idannonce }} w-2 h-2 rounded-full bg-white/50 transition-all duration-300 shadow-sm"></div>
                @endforeach
            </div>
        </div>
        <script>
            const carousel{{ $annonce->idannonce }} = document.getElementById("carousel{{ $annonce->idannonce }}");
            const dots{{ $annonce->idannonce }} = document.querySelectorAll(".dot{{ $annonce->idannonce }}");

            function scrollRight{{ $annonce->idannonce }}() {
                carousel{{ $annonce->idannonce }}.scrollBy({ left: carousel{{ $annonce->idannonce }}.clientWidth, behavior: "smooth" });
            }

            function scrollLeft{{ $annonce->idannonce }}() {
                carousel{{ $annonce->idannonce }}.scrollBy({ left: -carousel{{ $annonce->idannonce }}.clientWidth, behavior: "smooth" });
            }

            carousel{{ $annonce->idannonce }}.addEventListener("scroll", () => {
                let index = Math.round(carousel{{ $annonce->idannonce }}.scrollLeft / carousel{{ $annonce->idannonce }}.clientWidth);
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
            if(dots{{ $annonce->idannonce }}.length > 0) dots{{ $annonce->idannonce }}[0].classList.add("w-3", "h-3", "bg-white");
        </script>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative">
            <div class="lg:col-span-2">
                <div class="mb-6">
                    <h1 class="text-3xl font-extrabold text-slate-900 mb-2">{{ $annonce->titreannonce }}</h1>
                    <div class="flex flex-wrap items-center text-sm text-slate-600 gap-x-2">
                        <span>{{ $annonce->capacite ?? '?' }} voyageurs</span>
                        <span>·</span>
                        <span>{{ $annonce->nbchambres ?? '?' }} chambres</span>
                        <span>·</span>
                        <span class="font-medium underline">{{ $annonce->adresse->ville->nomville ?? 'Ville inconnue' }}</span>
                        
                        @if($annonce->avis_count > 0)
                            <span class="ml-2 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-orange-500">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-bold text-slate-900">{{ number_format($annonce->avis_avg_nombreetoiles, 1) }}</span>
                                <span class="underline">({{ $annonce->avis_count }} avis)</span>
                            </span>
                        @endif
                    </div>
                </div>
                <hr class="my-8 border-gray-200">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">À propos de ce logement</h2>
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $annonce->descriptionannonce }}</p>
                </div>
                <hr class="my-8 border-gray-200">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Ce que propose ce logement</h2>
                <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8">
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-800">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
                        </svg>
                        <span>{{ $annonce->typehebergement->nomtypehebergement ?? 'Logement entier' }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-800">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                        <span>{{ $annonce->nombreetoilesleboncoin ? $annonce->nombreetoilesleboncoin . ' étoiles' : 'Non classé' }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-800">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <span>{{ $annonce->possibilitefumeur ? 'Fumeur autorisé' : 'Non-fumeur' }}</span>
                    </div>
                </div>
                @if ($commoditesGroupees->isNotEmpty())
                    <hr class="my-8 border-gray-200">
                    <h2 class="text-xl font-bold text-slate-900 mb-6">Équipements</h2>
                    @foreach ($commoditesGroupees as $type => $commodites)
                        <div class="mb-6">
                            <h3 class="font-semibold text-slate-800 mb-3">{{ $type }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($commodites as $commodite)
                                    <div class="flex items-center text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-slate-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        {{ $commodite->nomcommodite }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <hr class="my-8 border-gray-200">
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Autres annonces de {{ $annonce->utilisateur->pseudonyme }}</h2>
                    <div class="flex overflow-x-auto space-x-4 pb-6 scrollbar-hide">
                        @foreach ($annonce->annonces as $autreAnnonce)
                            <a href="{{ route('annonce.view', ['id' => $autreAnnonce->idannonce]) }}" class="flex-shrink-0 w-64 block group">
                                <div class="relative overflow-hidden rounded-xl aspect-[4/3] mb-3">
                                    <img src="{{ $autreAnnonce->photos->first()->lienphoto ?? 'https://via.placeholder.com/300' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <h3 class="font-semibold text-slate-900 truncate">{{ $autreAnnonce->titreannonce }}</h3>
                                <p class="text-slate-500 text-sm">{{ $autreAnnonce->adresse->ville->nomville }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="sticky top-24"> {{-- Sticky permet de suivre le scroll --}}
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-6">
                        
                        <p class="text-lg font-bold text-slate-800 mb-4">Sélectionnez vos dates de séjour :</p>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Arrivée</label>
                                <div class="relative">
                                    <input type="date" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Départ</label>
                                <div class="relative">
                                    <input type="date" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                                </div>
                            </div>
                        </div>
                        <div class="bg-[#FEF9C3] text-[#854D0E] text-xs font-bold px-3 py-1.5 rounded-lg mb-6 w-fit inline-block">
                            Disponibilités non confirmées
                        </div>
                            @auth
                                <button class="w-full bg-[#EA580C] hover:bg-[#C2410C] text-white font-bold text-lg py-3 rounded-xl transition shadow-sm mb-6">
                                    Réserver
                                </button>
                            @else
                                <a href="{{ route('check.reservation', ['id' => $annonce->idannonce]) }}" 
                                    class="block text-center w-full bg-[#EA580C] hover:bg-[#C2410C] text-white font-bold text-lg py-3 rounded-xl transition shadow-sm mb-6">
                                    Réserver
                                </a>
                            @endauth
                        <hr class="border-gray-100 mb-6">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}" class="flex items-center gap-3 group">
                                {{-- Avatar avec Initiales --}}
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
                            
                            {{-- Chevron --}}
                            <a href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}" class="text-slate-400 hover:text-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="mt-4">
                            <span class="bg-[#FEF2F2] text-[#DC2626] text-xs font-bold px-2 py-1 rounded border border-[#FECACA] inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Réactif
                            </span>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection