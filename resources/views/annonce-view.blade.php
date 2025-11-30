@extends('layouts.app')
@section('content')
    <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-32">

        <div class="relative w-full h-80 mr-4">
            <button onclick="scrollLeft{{ $annonce->idannonce }}()"
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10">
                ‹
            </button>
            <button onclick="scrollRight{{ $annonce->idannonce }}()"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10">
                ›
            </button>
            <div id="carousel{{ $annonce->idannonce }}"
                class="w-full h-full overflow-x-auto flex gap-2 rounded-3xl scroll-smooth
                            snap-x snap-mandatory scrollbar-hide">
                @foreach ($annonce->photos ?? [] as $photo)
                    <div class="min-w-full h-full snap-start rounded-3xl overflow-hidden">
                        <img src="{{ $photo->lienphoto }}" loading="lazy" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
            <div id="dots{{ $annonce->idannonce }}" class="absolute bottom-1 w-full flex justify-center gap-2">
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
                    el.scrollTo({
                        left: 0,
                        behavior: "smooth"
                    });
                } else {
                    el.scrollBy({
                        left: el.clientWidth,
                        behavior: "smooth"
                    });
                }
            }

            function scrollLeft{{ $annonce->idannonce }}() {
                let el = carousel{{ $annonce->idannonce }};
                if (el.scrollLeft <= 5) {
                    el.scrollTo({
                        left: el.scrollWidth,
                        behavior: "smooth"
                    });
                } else {
                    el.scrollBy({
                        left: -el.clientWidth,
                        behavior: "smooth"
                    });
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

            dots{{ $annonce->idannonce }}[0].classList.add("w-3", "h-3", "bg-white");
        </script>
        <h1 class=" text-2xl font-bold mb-3">{{ $annonce->titreannonce }}</h1>
        <div class=" text-sm mb-3">
            @php $totalPlaces = 0; @endphp
            @foreach ($annonce->chambres ?? [] as $chambre)
                @php
                    $cap = $chambre->capacitechambre ?? ($chambre->capacite_chambre ?? null);
                    $totalPlaces += (int) ($cap ?? 0);
                @endphp
            @endforeach
            {{ $annonce->typehebergement->nomtypehebergement }}
            <span class="mx-sm text-neutral inline-block font-bold">·</span>
            {{ $totalPlaces ?? '?' }} pers.
            <span class="mx-sm text-neutral inline-block font-bold">·</span>
            {{ $annonce->chambres->count() ?? '?' }} chambres
            <span class="mx-sm text-neutral inline-block font-bold">·</span>
            {{ $annonce->adresse->ville->nomville ?? 'Ville inconnue' }}
            <span class="mx-sm text-neutral inline-block font-bold">·</span>
            <div class="inline-flex">
                <svg class=" mr-0.5" style="fill: #b84a14; height: 14px" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" data-title="SvgStarFill" fill="currentColor"
                    class="fill-current shrink-0 text-current w-sz-16 h-sz-16 mr-sm" data-spark-component="icon"
                    aria-hidden="true" focusable="false">
                    <path
                        d="M11.0437 2.29647C10.7942 2.46286 10.5447 2.71245 10.3784 3.04524L8.29938 7.6211L3.55925 8.28668C3.22661 8.28668 2.97713 8.45307 2.64449 8.61947C2.39501 8.86906 2.14553 9.11865 2.06237 9.45144C1.97921 9.86742 1.97921 10.2002 2.06237 10.533C2.14553 10.8658 2.31185 11.1986 2.56133 11.4482L6.05405 14.9425L5.22245 19.9343C5.13929 20.2671 5.22245 20.5999 5.30561 20.9327C5.38877 21.2655 5.63825 21.5151 5.88773 21.6814C6.13721 21.8478 6.46985 22.0142 6.8025 22.0142C7.13514 22.0142 7.46778 21.931 7.71726 21.8478L12.0416 19.5183L16.3659 21.8478C16.6154 22.0142 16.948 22.0974 17.2807 22.0142C17.6133 22.0142 17.9459 21.8478 18.1954 21.6814C18.4449 21.5151 18.6944 21.1823 18.7775 20.9327C18.8607 20.5999 18.9439 20.2671 18.8607 19.9343L18.0291 14.9425L21.4387 11.365C21.6882 11.1154 21.8545 10.8658 21.9376 10.533C22.0208 10.2002 22.0208 9.86742 21.9376 9.53464C21.8545 9.20185 21.6882 8.95225 21.4387 8.70266C21.1892 8.45307 20.8566 8.36987 20.6071 8.28668L15.8669 7.5379L13.7048 3.04524C13.5385 2.71245 13.3721 2.46286 13.0395 2.29647C12.79 2.13007 12.5405 2.04688 12.2911 2.04688H11.9584C11.5426 2.13007 11.2931 2.21327 11.0437 2.29647Z">
                    </path>
                </svg>
                <span class="opacity-75">{{ number_format($annonce->avis_avg_nombreetoiles, 1) }} <span
                        class=" opacity-50">({{ $annonce->avis_count }})</span></span>
            </div>
        </div>
        <div class="text-sm">
            <p class=" mb-3">
                @php
                    $price = number_format($annonce->prixnuitee, 2, '.', '');
                    $price = preg_replace('/\.00$/', '', $price);
                @endphp
                à partir de <span class=" font-black">{{ $price }} € / nuit</span>
            </p>
                <p>Publié le 
                    <span class="font-bold">
                        {{ $annonce->datePublication ? \Carbon\Carbon::parse($annonce->datePublication->date)->format('d/m/Y') : 'Date inconnue' }}
                    </span>
                </p>
        </div>
        <hr class="my-6 opacity-50">

        <h2 class="text-xl font-black mb-6">Critères</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500 mb-0.5">Nombre d'étoiles</span>
                    <span class="font-bold text-sm text-neutral-900">
                        {{ $annonce->nombreetoilesleboncoin ? $annonce->nombreetoilesleboncoin . ' étoiles' : 'Non classé' }}
                    </span>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500 mb-0.5">Capacité</span>
                    <span class="font-bold text-sm text-neutral-900">
                        {{ $totalPlaces ?? '?' }} personnes
                    </span>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                </svg>
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500 mb-0.5">Type d'hébergement</span>
                    <span class="font-bold text-sm text-neutral-900">
                        {{ $annonce->typehebergement->nomtypehebergement ?? 'Non spécifié' }}
                    </span>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-neutral-800 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500 mb-0.5">Nombre de chambres</span>
                    <span class="font-bold text-sm text-neutral-900">
                        {{ $annonce->chambres->count() ?? 0 }} chambres
                    </span>
                </div>
            </div>
        </div>

            <div class=" mb-10">
                <h2 class="text-xl font-black mb-4">Conditions de l'hébergement</h2>
                <ul class="space-y-3">
                    <li class="flex items-center gap-3 text-neutral-800">
                        <!-- Icône d'horloge -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            Arrivée : <span class="font-medium">{{ $annonce->heurearrivee->heure ?? 'Non spécifiée' }}</span>
                        </span>
                    </li>
                    <li class="flex items-center gap-3 text-neutral-800">
                        <!-- Icône d'horloge -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            Départ : <span class="font-medium">{{ $annonce->heuredepart->heure ?? 'Non spécifiée' }}</span>
                        </span>
                    </li>
                </ul>
            </div>
            <p class=" mb-10">Publié par 
                <a class=" underline" href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}">
                    {{ $annonce->utilisateur->pseudonyme ?? 'Utilisateur inconnu' }}
                </a>
            </p>
                <h2 class="text-xl font-black mb-4">Annonces similaires</h2>
                @foreach ($annonce->annonces as $annonce)
            <a href="{{ route('annonce.view', ['id' => $annonce->idannonce]) }}" class="block mb-4">
                <div class="flex border rounded-lg">
                    <img src="{{ $annonce->photos->first()->lienphoto ?? '' }}" alt="{{ $annonce->titreannonce }}"
                        class=" w-44 h-32 object-cover rounded-lg mr-4">
                    <div class=" flex flex-col justify-around py-2">
                        <p class=" font-bold text-xl">{{ $annonce->titreannonce }}</p>
                        <p>
                            @php
                                $price = number_format($annonce->prixnuitee, 2, '.', '');
                                $price = preg_replace('/\.00$/', '', $price);
                            @endphp
                            à partir de <span class=" font-black">{{ $price }} € / nuit</span>
                        </p>
                        <p>{{ $annonce->adresse->ville->nomville }} {{ $annonce->adresse->ville->codepostal }}</p>
                    </div>
                </div>
            </a>
        @endforeach
        </div>

    </div>
@endsection
