<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes favoris</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Mes favoris</h1>
            
            @if($favorites->isEmpty())
                <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                    <div class="text-5xl mb-4">❤️</div>
                    <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune annonce favorite</h3>
                    <p class="text-gray-500 mt-2 mb-6">Explorez les annonces et cliquez sur le cœur pour les retrouver ici.</p>
                    <a href="{{ route('home') }}" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Explorer les annonces</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favorites as $annonce)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group">
                            <div class="relative h-48 bg-gray-100">
                                @if($annonce->photos->isNotEmpty())
                                    <img src="{{ $annonce->photos->first()->lienphoto }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Sans photo</div>
                                @endif
                                <span class="absolute top-2 right-2 bg-white rounded-full p-1.5 shadow-sm text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                    </svg>
                                </span>
                            </div>
                            
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-gray-900 line-clamp-1 text-lg">{{ $annonce->titreannonce }}</h3>
                                    <span class="font-bold text-gray-900 border border-gray-100 bg-gray-50 px-2 py-0.5 rounded">{{ $annonce->prixnuitee }} €</span>
                                </div>
                                
                                <p class="text-sm text-gray-500 mb-4 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ $annonce->adresse->ville->nomville ?? 'Lieu inconnu' }}
                                </p>
                                
                                <a href="{{ route('annonce.view', ['id' => $annonce->idannonce]) }}" class="block w-full text-center bg-gray-50 hover:bg-orange-50 text-gray-700 hover:text-orange-700 font-bold py-2 rounded-lg transition text-sm border border-gray-200 hover:border-orange-200">
                                    Voir l'annonce
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endsection
</x-app-layout>
