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
        <h2 class=" text-xl font-black">Description</h2>
        <p class=" mt-3">{{ $annonce->descriptionannonce }}</p>
        <hr class="my-6 opacity-50">
        <h2 class=" text-xl font-black">Critères</h2>
        <div class=" grid">

        </div>
        <hr class="my-6 opacity-50">
        <h2 class=" text-xl font-black">Conditions de l'hébergement</h2>
        <hr class="my-6 opacity-50">
        <p>Publié par 
            <a class=" underline" href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}">
                {{ $annonce->utilisateur->pseudonyme ?? 'Utilisateur inconnu' }}
            </a>
        </p>
    </div>
@endsection
