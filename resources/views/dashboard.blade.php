@php
    $isSuperAdminOrNoRoles = Auth::user()->isSuperAdminOrNoRoles();
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon compte') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="bg-[#f8f9fb] min-h-screen pb-12">
            <main id="mainContent" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <div class="flex flex-col md:flex-row gap-6 mb-8">
                    <div class="bg-white border border-gray-200 flex w-full grow flex-col rounded-xl shadow-sm">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-6">

                                    <div class="relative flex-shrink-0">
                                        <div
                                            class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-200 flex items-center justify-center text-3xl font-bold text-gray-400">
                                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                                                <img class="h-full w-full object-cover"
                                                    src="{{ Auth::user()->profile_photo_url }}"
                                                    alt="{{ Auth::user()->pseudonyme }}" />
                                            @else
                                                {{ substr(Auth::user()->pseudonyme, 0, 1) }}
                                            @endif
                                        </div>
                                        @if ($isSuperAdminOrNoRoles)
                                            <a href="{{ route('user.edit') }}"
                                                class="absolute bottom-0 -right-1 bg-white text-gray-700 p-2 rounded-full shadow-md border border-gray-100 hover:bg-gray-50 z-20 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path
                                                        d="M21.731 2.269a2.625 2.625 0 113.71 3.71l-9.399 9.399-1.127 1.127a2.25 2.25 0 01-1.59.659h-5.376a.75.75 0 01-.75-.75v-5.376a2.25 2.25 0 01.659-1.59l1.128-1.127 9.399-9.399zM8.679 13.72a.75.75 0 10-1.06-1.06L5.25 15.031v2.421l2.421.26 2.369-2.369z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="flex flex-col">
                                        <{{ $isSuperAdminOrNoRoles ? 'a' : 'p'}} href="{{ route('user.edit') }}" class="group">
                                            <h2 class="text-2xl font-bold text-gray-900 group-hover:underline mb-1">
                                                @if (Auth::user()->particulier)
                                                    {{ Auth::user()->particulier->prenomutilisateur }}
                                                    {{ Auth::user()->particulier->nomutilisateur }}
                                                @elseif(Auth::user()->professionnels)
                                                    {{ Auth::user()->professionnels->nomsociete }}
                                                @else
                                                    {{ Auth::user()->pseudonyme }}
                                                @endif
                                            </h2>
                                        </{{ $isSuperAdminOrNoRoles ? 'a' : 'p'}}>
                                        @forelse(auth()->user()->roles as $role)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border border-orange-200 bg-orange-50 text-orange-700 text-xs font-semibold shadow-sm w-fit">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                        @endforelse
                                        @if ($isSuperAdminOrNoRoles)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <span class="font-bold text-gray-900">
                                                    {{ number_format(Auth::user()->avis_recus_avg_nombreetoiles ?? 0, 1) }}/5
                                                </span>
                                                <span class="ml-1">({{ Auth::user()->avis_recus_count ?? 0 }}
                                                    avis)</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if ($isSuperAdminOrNoRoles)
                                    <a class="text-gray-900 font-bold hover:underline"
                                        href="{{ route('user.edit') }}">Modifier</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($isSuperAdminOrNoRoles)
                        <div
                            class="md:w-96 bg-white border border-gray-200 rounded-xl shadow-sm p-6 pl-12 relative overflow-hidden flex flex-col justify-center">
                            <div class="absolute -left-20 top-0 h-full flex items-center"><svg width="200" height="200"
                                    viewBox="0 0 385 418" fill="none">
                                    <circle cx="192.629" cy="225.167" r="136" fill="#ea580c" fill-opacity="0.1">
                                    </circle>
                                </svg></div>
                            <h2 class="text-lg font-bold text-gray-900 z-10">Porte-monnaie</h2>
                            <div class="mt-2 z-10"><span
                                    class="text-3xl font-bold text-gray-900">{{ number_format(auth()->user()->solde, 2) }}
                                    €</span></div>
                            <span class="text-sm text-gray-500 z-10">Solde disponible</span>
                        </div>
                    @endif
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @can('user.verifID')
                        <a href="{{ route('services-petites-annonces.index') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Verifier Comptes</h2>
                                <p class="text-gray-500 text-sm mt-1">Verifier les comptes des utilisateurs</p>
                            </div>
                        </a>
                    @endcan

                    @can('service.incidents')
                        <a href="{{ route('services.incidents.index') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div class="w-10 h-10 flex-shrink-0 bg-red-50 rounded-lg flex items-center justify-center text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Gestion Incidents</h2>
                                <p class="text-gray-500 text-sm mt-1">Gérer les litiges et signalements</p>
                            </div>
                        </a>
                    @endcan

                    @can('service.catalogue')
                        <a href="{{ route('services.catalogue.index') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div class="w-10 h-10 flex-shrink-0 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Catalogue & Types</h2>
                                <p class="text-gray-500 text-sm mt-1">Gérer les équipements et types d'hébergement</p>
                            </div>
                        </a>
                    @endcan

                    @can('annonce.status')
                        <a href="{{ route('services.annonces') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Status Nouvelles Annonces</h2>
                                <p class="text-gray-500 text-sm mt-1">Verifier le status des nouvelles annonces</p>
                            </div>
                        </a>
                    @endcan

                    @can('annonce.immobilier')
                        <a href="{{ route('services.immobilier.annonces') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Service Immobilier</h2>
                                <p class="text-gray-500 text-sm mt-1">Vérifier les annonces (identité confirmée)</p>
                            </div>
                        </a>
                    @endcan

                    @can('service.inscription')
                        <a href="{{ route('services.inscription.index') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-green-50 rounded-lg flex items-center justify-center text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Service Inscription</h2>
                                <p class="text-gray-500 text-sm mt-1">Gérer les inscriptions non vérifiées</p>
                            </div>
                        </a>
                    @endcan

                    @can('directeur.petite-annonce.statistiques')
                        <a href="{{ route('directeur.petite-annonce.statistiques') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Statistiques Locations</h2>
                                <p class="text-gray-500 text-sm mt-1">Tableau de bord état des locations</p>
                            </div>
                        </a>
                    @endcan     
                    @if ($isSuperAdminOrNoRoles)
                        <a href="{{ route('user.annonces') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Annonces</h2>
                                <p class="text-gray-500 text-sm mt-1">Gérer mes annonces déposées</p>
                            </div>
                        </a>

                        <a href="{{ route('user.mes-reservations') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Réservations</h2>
                                <p class="text-gray-500 text-sm mt-1">Vos séjours</p>
                            </div>
                        </a>

                        <a href="{{ route('user.spaces') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-green-50 rounded-lg flex items-center justify-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Profil</h2>
                                <p class="text-gray-500 text-sm mt-1">Modifier mon profil public</p>
                            </div>
                        </a>

                        <a href="{{ route('user.settings') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.212 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Paramètres</h2>
                                <p class="text-gray-500 text-sm mt-1">Infos privées</p>
                            </div>
                        </a>

                        <a href="{{ route('user.security') }}"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Connexion et sécurité</h2>
                                <p class="text-gray-500 text-sm mt-1">Sécurité du compte</p>
                            </div>
                        </a>
                        
                    @endif



                </div>

                <div class="mt-12 flex justify-end md:justify-start">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2.5 border border-gray-900 text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                            Me déconnecter
                        </button>
                    </form>
                </div>

            </main>
        </div>
    @endsection
</x-app-layout>
