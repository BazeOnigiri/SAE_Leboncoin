@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 mb-5 shadow-sm">
    <div class="flex justify-between items-start mb-6">
        <div class="flex gap-4">
            <img class="h-16 w-16 rounded-full object-cover border border-gray-100" 
                src="{{ $user->profile_photo_url }}"
                alt="Photo de profil de {{ $user->pseudonyme }}">

            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-xl font-bold text-gray-900">{{ $user->pseudonyme }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 mr-1">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        Profil recommandé // A Faire
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400 cursor-pointer hover:text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center text-gray-700 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Pièce d'identité vérifiée // A Faire
                    </div>
                    <div class="flex items-center text-gray-700 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                        Numéro de téléphone vérifié // A Faire
                    </div>
                </div>
            </div>
        </div>

        <button class="p-2 border border-gray-300 rounded-full hover:bg-gray-50 text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
            </svg>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 text-sm text-gray-600">
        
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
            </svg>
            <span>{{ $user->created_at?->format('F Y') ?? '// A Faire' }}</span>
        </div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Répond dans les 3 heures // A Faire</span>
        </div>

        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            <span>{{ $user->adresse?->ville?->departement?->nomdepartement ?? 'Non renseigné' }}</span>
        </div>

        <div class="flex items-center md:justify-end">
    <div class="flex mr-1">
        @php
            // On récupère la moyenne calculée par le contrôleur
            // On arrondit à l'entier le plus proche pour l'affichage des étoiles
            $note = round($user->avis_recus_avg_nombreetoiles); 
        @endphp

        {{-- On boucle 5 fois pour afficher les 5 étoiles --}}
        @foreach(range(1, 5) as $i)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
                class="w-4 h-4 {{ $i <= $note ? 'text-orange-500' : 'text-gray-300' }}">
                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
            </svg>
        @endforeach
    </div>

    {{-- Affichage du texte (ex: "4.8/5 (6 avis)") --}}
    <span class="font-bold text-gray-900 text-sm">
        @if($user->avis_recus_count > 0)
            {{ $user->avis_recus_count }} avis
        @else
            Aucun avis
        @endif
    </span>
</div>
    </div>
</div>
        <p class=" font-bold">{{ $user->annonces_publiees_count }} annonce.s</p>
        @foreach ($user->annoncesPubliees as $annonce)
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
    @endsection
