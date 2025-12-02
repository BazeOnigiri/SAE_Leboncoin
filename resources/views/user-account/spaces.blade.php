<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Profil</h1>

            <!-- Bloc Principal -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <!-- Ligne 1 : Profil Public -->
                <a href="#" class="flex items-center justify-between p-6 hover:bg-gray-50 transition border-b border-gray-100 group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition">Voir mon profil public</h3>
                            <p class="text-gray-500 text-sm">C'est ce que les autres utilisateurs voient</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- Ligne 2 : Coordonnées -->
                <a href="{{ route('user.settings') }}" class="flex items-center justify-between p-6 hover:bg-gray-50 transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 text-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-orange-600 transition">Mes coordonnées</h3>
                            <p class="text-gray-500 text-sm">Nom, prénom, email, téléphone, adresse...</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

            </div>

            <!-- Section Badges -->
            <h2 class="text-xl font-bold text-gray-900 mb-4 mt-8">Mes badges et vérifications</h2>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Email vérifié</h3>
                    <p class="text-gray-500 text-sm">Votre email a été validé avec succès.</p>
                </div>
            </div>

        </div>
    </div>
    @endsection
</x-app-layout>