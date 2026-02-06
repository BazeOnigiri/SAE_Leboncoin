@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Annonce Title -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $annonce->titre }}</h1>
        
        <!-- Annonce Description -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">Description</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $annonce->description }}</p>
        </div>

        <!-- Annonce Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Détails</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($annonce->capacite)
                    <div class="flex items-center">
                        <span class="font-medium text-gray-700 mr-2">Capacité:</span>
                        <span class="text-gray-600">{{ $annonce->capacite }} personnes</span>
                    </div>
                @endif
                
                @if($annonce->nbchambres !== null)
                    <div class="flex items-center">
                        <span class="font-medium text-gray-700 mr-2">Chambres:</span>
                        <span class="text-gray-600">{{ $annonce->nbchambres }}</span>
                    </div>
                @endif
                
                @if($annonce->tarifnuit)
                    <div class="flex items-center">
                        <span class="font-medium text-gray-700 mr-2">Prix par nuit:</span>
                        <span class="text-gray-600">{{ number_format($annonce->tarifnuit, 2, ',', ' ') }} €</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Commodites Section -->
        @if($commoditesGroupees->isNotEmpty())
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Équipements et services</h2>
                
                @foreach($commoditesGroupees as $categorieName => $commodites)
                    <div class="mb-6 last:mb-0">
                        <h3 class="text-lg font-medium text-gray-700 mb-3 border-b pb-2">{{ $categorieName }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($commodites as $commodite)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ $commodite->nomcommodite }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>
</div>
@endsection
