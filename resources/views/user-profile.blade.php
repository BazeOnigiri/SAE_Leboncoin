@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
        <div class=" p-3 rounded-xl border mb-5">
            <div class=" flex mb-4">
                <img class=" rounded-full border mr-6" src="{{ $user->profile_photo_url }}"
                    alt="Photo de profil de {{ $user->pseudonyme }}" width="100" height="100">
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ $user->pseudonyme }}</h1>
                    <p class="text-gray-600 mb-1">Info supp ex : piece d'identité vérifiée</p>
                    <p class="text-gray-600 mb-1">Info supp ex : Numéro de téléphone vérifié</p>
                </div>
            </div>
            <div class="flex">
                <span>Localisation</span>
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
