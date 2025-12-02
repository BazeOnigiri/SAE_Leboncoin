<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil & Espaces</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Profil & Espaces</h1>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 flex justify-between items-center cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-xl font-bold text-gray-500">
                        {{ substr(Auth::user()->prenomutilisateur, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Mon profil public</h3>
                        <p class="text-gray-500 text-sm">Voir ce que les autres voient</p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>

            <h2 class="text-xl font-bold text-gray-900 mb-4 mt-8">Mes espaces</h2>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4 flex justify-between items-center cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded bg-blue-50 flex items-center justify-center text-blue-600">💼</div>
                    <div>
                        <h3 class="font-bold text-lg">Espace Candidat</h3>
                        <p class="text-gray-500 text-sm">Gérer mon CV et mes candidatures</p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4 flex justify-between items-center cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded bg-green-50 flex items-center justify-center text-green-600">🏠</div>
                    <div>
                        <h3 class="font-bold text-lg">Espace Locataire</h3>
                        <p class="text-gray-500 text-sm">Gérer mon dossier de location</p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>