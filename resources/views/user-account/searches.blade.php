<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes recherches</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Mes recherches</h1>
            
            <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune recherche sauvegardée</h3>
                <p class="text-gray-500 mt-2 mb-6">Sauvegardez vos critères de recherche pour gagner du temps.</p>
                <a href="{{ route('home') }}" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Faire une recherche</a>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
