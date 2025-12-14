<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mon Compte</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center gap-6 mb-8">
                <div class="w-20 h-20 rounded-full bg-gray-200 overflow-hidden">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->pseudonyme }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ auth()->user()->pseudonyme }}</h1>
                    <a href="{{ route('user.profile', ['id' => auth()->user()->idutilisateur]) }}" class="text-orange-600 hover:underline text-sm font-semibold">Voir mon profil public</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <a href="{{ route('user.settings') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group">
                    <div class="text-orange-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Informations personnelles</h3>
                    <p class="text-gray-500 text-sm">Mettez à jour vos coordonnées et votre adresse.</p>
                </a>

                <a href="{{ route('user.security') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group">
                    <div class="text-orange-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Connexion et sécurité</h3>
                    <p class="text-gray-500 text-sm">Mettez à jour votre mot de passe et sécurisez votre compte.</p>
                </a>

                <a href="{{ route('user.annonces') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group">
                    <div class="text-orange-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Mes annonces</h3>
                    <p class="text-gray-500 text-sm">Gérez vos biens en location et vos disponibilités.</p>
                </a>

                <a href="{{ route('user.mes-reservations') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group cursor-pointer">
                    <div class="text-orange-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Mes réservations</h3>
                    <p class="text-gray-500 text-sm">Retrouvez vos séjours passés et à venir.</p>
                </a>

            </div>
        </div>
    </div>
    @endsection
</x-app-layout>