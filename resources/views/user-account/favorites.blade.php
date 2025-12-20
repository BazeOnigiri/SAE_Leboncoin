<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes favoris</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Mes favoris</h1>
            
            <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                <div class="text-5xl mb-4">❤️</div>
                <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune annonce favorite</h3>
                <p class="text-gray-500 mt-2 mb-6">Explorez les annonces et cliquez sur le cœur pour les retrouver ici.</p>
                <a href="{{ route('home') }}" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Explorer les annonces</a>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
