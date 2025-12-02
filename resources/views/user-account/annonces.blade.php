<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes annonces</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Annonces</h1>
            
            <div class="flex border-b border-gray-300 mb-6">
                <button class="px-6 py-3 border-b-2 border-black font-bold text-gray-900">En ligne ({{ $annonces->count() }})</button>
                <button class="px-6 py-3 text-gray-500 hover:text-gray-700">À pourvoir (0)</button>
                <button class="px-6 py-3 text-gray-500 hover:text-gray-700">Inactives (0)</button>
            </div>

            @if($annonces->isEmpty())
                <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                    <div class="text-5xl mb-4">📦</div>
                    <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune annonce en ligne</h3>
                    <p class="text-gray-500 mt-2 mb-6">C'est le moment de faire du tri !</p>
                    <a href="#" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700">Déposer une annonce</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($annonces as $annonce)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-48 h-32 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                @if($annonce->photos->isNotEmpty())
                                    <img src="{{ $annonce->photos->first()->lienphoto }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">Sans photo</div>
                                @endif
                            </div>
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900">{{ $annonce->titreannonce }}</h3>
                                    <p class="text-gray-900 font-bold text-sm mt-1">{{ $annonce->prixnuitee }} € / nuit</p>
                                    <p class="text-gray-500 text-xs mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $annonce->adresse->ville->nomville ?? 'Lieu inconnu' }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-6 mt-4 text-sm font-bold text-gray-700 border-t border-gray-100 pt-3">
                                    <button class="flex items-center gap-1 hover:text-orange-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Modifier
                                    </button>
                                    <button class="flex items-center gap-1 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endsection
</x-app-layout>