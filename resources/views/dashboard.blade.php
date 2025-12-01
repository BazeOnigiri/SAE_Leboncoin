<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen pb-12">
        <main id="mainContent" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div class="flex flex-col md:flex-row gap-6 mb-8">
                <div class="bg-white border border-gray-200 flex w-full grow flex-col rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="relative w-24 h-24 flex-shrink-0">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-gray-100 border border-gray-200 flex items-center justify-center text-3xl font-bold text-gray-400">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                                            <img class="h-full w-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->prenomutilisateur }}" />
                                        @else
                                            {{ substr(Auth::user()->prenomutilisateur, 0, 1) }}
                                        @endif
                                    </div>
                                    <a href="{{ route('profile.show') }}" class="absolute bottom-0 right-0 bg-white p-2 rounded-lg shadow-md border border-gray-100 hover:bg-gray-50 text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path d="M21.731 2.269a2.625 2.625 0 113.71 3.71l-9.399 9.399-1.127 1.127a2.25 2.25 0 01-1.59.659h-5.376a.75.75 0 01-.75-.75v-5.376a2.25 2.25 0 01.659-1.59l1.128-1.127 9.399-9.399zM8.679 13.72a.75.75 0 10-1.06-1.06L5.25 15.031v2.421l2.421.26 2.369-2.369z" />
                                        </svg>
                                    </a>
                                </div>

                                <div class="flex flex-col">
                                    <a href="{{ route('profile.show') }}" class="group">
                                        <h2 class="text-2xl font-bold text-gray-900 group-hover:underline mb-1">
                                            {{ Auth::user()->prenomutilisateur }} {{ Auth::user()->nomutilisateur }}
                                        </h2>
                                    </a>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <div class="flex text-yellow-500 mr-2">
                                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M11.0437 2.29647C10.7942 2.46286 10.5447 2.71245 10.3784 3.04524L8.29938 7.6211L3.55925 8.28668C3.22661 8.28668 2.97713 8.45307 2.64449 8.61947C2.39501 8.86906 2.14553 9.11865 2.06237 9.45144C1.97921 9.86742 1.97921 10.2002 2.06237 10.533C2.14553 10.8658 2.31185 11.1986 2.56133 11.4482L6.05405 14.9425L5.22245 19.9343C5.13929 20.2671 5.22245 20.5999 5.30561 20.9327C5.38877 21.2655 5.63825 21.5151 5.88773 21.6814C6.13721 21.8478 6.46985 22.0142 6.8025 22.0142C7.13514 22.0142 7.46778 21.931 7.71726 21.8478L12.0416 19.5183L16.3659 21.8478C16.6154 22.0142 16.948 22.0974 17.2807 22.0142C17.6133 22.0142 17.9459 21.8478 18.1954 21.6814C18.4449 21.5151 18.6944 21.1823 18.7775 20.9327C18.8607 20.5999 18.9439 20.2671 18.8607 19.9343L18.0291 14.9425L21.4387 11.365C21.6882 11.1154 21.8545 10.8658 21.9376 10.533C22.0208 10.2002 22.0208 9.86742 21.9376 9.53464C21.8545 9.20185 21.6882 8.95225 21.4387 8.70266C21.1892 8.45307 20.8566 8.36987 20.6071 8.28668L15.8669 7.5379L13.7048 3.04524C13.5385 2.71245 13.3721 2.46286 13.0395 2.29647C12.79 2.13007 12.5405 2.04688 12.2911 2.04688H11.9584C11.5426 2.13007 11.2931 2.21327 11.0437 2.29647Z"></path></svg>
                                        </div>
                                        <span class="font-bold text-gray-900">5</span>
                                        <span class="ml-1">(1 avis)</span>
                                    </div>
                                </div>
                            </div>
                            <a class="text-gray-900 font-bold hover:underline" href="{{ route('profile.show') }}">Modifier</a>
                        </div>
                    </div>
                </div>

                <a href="#" class="md:w-96 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden relative flex items-center hover:shadow-md transition-shadow">
                    <div class="absolute -left-20">
                        <svg width="200" height="200" viewBox="0 0 385 418" fill="none"><circle cx="192.629" cy="225.167" r="136" fill="#ea580c" fill-opacity="0.1"></circle></svg>
                    </div>
                    <div class="p-6 pl-12 relative z-10">
                        <h2 class="text-lg font-bold text-gray-900">Porte-monnaie</h2>
                        <div class="mt-2">
                            <span class="text-3xl font-bold text-gray-900">0,00 €</span>
                        </div>
                        <span class="text-sm text-gray-500">Solde disponible</span>
                    </div>
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                
                <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Annonces</h2>
                        <p class="text-gray-500 text-sm mt-1">Gérer mes annonces déposées</p>
                    </div>
                </a>

                <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-green-50 rounded-lg flex items-center justify-center text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Transactions</h2>
                        <p class="text-gray-500 text-sm mt-1">Suivre mes achats et mes ventes</p>
                    </div>
                </a>

                <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Réservation de vacances</h2>
                        <p class="text-gray-500 text-sm mt-1">Retrouver vos réservations en tant qu’hôte ou voyageur</p>
                    </div>
                </a>

                <a href="{{ route('profile.show') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Profil & Espaces</h2>
                        <p class="text-gray-500 text-sm mt-1">Modifier mon profil public, accéder à mes avis</p>
                    </div>
                </a>

                <a href="{{ route('profile.show') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.212 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Paramètres</h2>
                        <p class="text-gray-500 text-sm mt-1">Compléter et modifier mes informations privées</p>
                    </div>
                </a>

                <a href="{{ route('profile.show') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-gray-900">Connexion et sécurité</h2>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Protéger mon compte et consulter son indice</p>
                    </div>
                </a>

                <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Factures</h2>
                        <p class="text-gray-500 text-sm mt-1">Consulter et télécharger les factures</p>
                    </div>
                </a>

                <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-center justify-between">
                <div class="flex items-center gap-4">
                        <div class="w-10 h-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Aide</h2>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
                </a>

            </div>

            <div class="mt-12 flex justify-end md:justify-start">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 border border-gray-900 text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                        Me déconnecter
                    </button>
                </form>
            </div>

        </main>
    </div>
    @endsection
</x-app-layout>