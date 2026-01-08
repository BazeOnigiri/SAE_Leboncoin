@php
    $user = auth()->user();
    $isSuperAdminOrNoRoles = !$user || $user->roles->isEmpty() || $user->hasRole('Super Admin');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'leboncoin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places" async defer></script>

    <meta name="description" content="Découvrez des annonces immobilières et de location de vacances sur Leboncoin.">

    @livewireStyles

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Nunito Sans', 'Nunito Sans Fallback';
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .snap-x {
            scroll-snap-type: x mandatory;
        }

        .snap-start {
            scroll-snap-align: start;
        }
    </style>

</head>

<body class="bg-[#f8f9fb] font-sans text-gray-900 antialiased">
    <header id="main-header"
        class="w-full border-b border-gray-200 bg-white py-3 sticky top-0 z-40 left-0 transition-shadow duration-200">
        <div class="mx-auto h-16 flex items-center justify-between gap-4 max-w-6xl px-6 md:px-12 xl:px-6" x-data="{ mobileMenuOpen: false }">

            <div class="flex items-center gap-4">
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 -ml-2 text-gray-600 hover:text-orange-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <a href="{{ $isSuperAdminOrNoRoles ? '/' : '/dashboard' }}" class="flex-shrink-0">
                    <img 
                        src="/assets/Leboncoin_logo.svg" 
                        alt="Logo leboncoin" 
                        class="h-8 w-auto object-contain" 
                    />
                </a>
            </div>

            <!-- Mobile Menu Overlay -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 bg-black/50 md:hidden"
                 @click="mobileMenuOpen = false"
                 style="display: none;">
            </div>

            <!-- Mobile Menu Panel -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 z-50 w-4/5 max-w-sm bg-white shadow-xl overflow-y-auto md:hidden"
                 style="display: none;">
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <img src="/assets/Leboncoin_logo.svg" alt="Logo" class="h-8 w-auto">
                        <button @click="mobileMenuOpen = false" class="p-2 -mr-2 text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        @auth
                            <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-xl border border-orange-100">
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->prenomutilisateur }}">
                                <div>
                                    <p class="font-bold text-gray-900">{{ auth()->user()->pseudonyme }}</p>
                                    <a href="{{ route('dashboard') }}" class="text-sm text-orange-600 font-medium hover:underline">Mon tableau de bord</a>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('auth.check') }}" class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-gray-900">Se connecter</span>
                            </a>
                        @endauth

                        @if($isSuperAdminOrNoRoles)
                            <hr class="border-gray-100">
                            
                            <nav class="space-y-4">
                                <a href="{{ route('annonce.create') }}" class="flex items-center gap-3 text-gray-700 font-medium hover:text-orange-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Déposer une annonce
                                </a>

                                <a href="{{ route('user.searches') }}" class="flex items-center gap-3 text-gray-700 font-medium hover:text-orange-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                    Mes recherches
                                </a>

                                <a href="{{ route('user.favorites') }}" class="flex items-center gap-3 text-gray-700 font-medium hover:text-orange-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                    Favoris
                                </a>
                            </nav>

                        
                            </nav>
                        @endif
                        
                        <hr class="border-gray-100">
                        
                        <div class="space-y-4">
                            <a href="{{ route('help.faq') }}" class="block text-sm font-medium text-gray-700 hover:text-orange-600 transition-colors">Centre d'aide</a>
                        </div>
                    </div>
                </div>
            </div>

            @if($isSuperAdminOrNoRoles)
                <a href="{{ route('annonce.create') }}" id="header-create-annonce-btn"
                    class="hidden md:flex items-center gap-2 bg-[#ea580c] hover:bg-[#c2410c] text-white transition-colors  font-bold py-2.5 px-5 rounded-xl  shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Déposer une annonce</span>
                </a>


                <nav class="flex items-center gap-6 text-gray-700 shrink-0">
                    <a href="{{ route('help.faq') }}" id="header-help-link" class="relative hidden md:flex flex-col items-center gap-1 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 group-hover:text-black transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                        </svg>
                        <span class="text-xs font-medium group-hover:text-black transition-colors">Aide</span>

                        <span class="absolute -bottom-3 left-1/2 w-0 h-[3px] -translate-x-1/2 bg-orange-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('user.searches') }}" id="header-searches-link" class="relative hidden md:flex flex-col items-center gap-1 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6 group-hover:text-black transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="text-xs font-medium group-hover:text-black transition-colors">Mes recherches</span>

                        <span class="absolute -bottom-3 left-1/2 w-0 h-[3px] -translate-x-1/2 bg-orange-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    </a>

                    <a href="{{ route('user.favorites') }}" id="header-favorites-link" class="relative hidden md:flex flex-col items-center gap-1 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6 group-hover:text-black transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span class="text-xs font-medium group-hover:text-black transition-colors">Favoris</span>

                        <span class="absolute -bottom-3 left-1/2 w-0 h-[3px] -translate-x-1/2 bg-orange-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    
            @else
                <nav>
            @endif

                @auth
                    <a href="{{ route('dashboard') }}" id="header-user-dashboard-link" class="relative hidden md:flex flex-col items-center gap-1 group">
                        <img class="rounded-full h-6 z-6" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->prenomutilisateur }}">
                        <span class="text-xs font-medium group-hover:text-black transition-colors">{{ auth()->user()->pseudonyme }}</span>

                        <span class="absolute -bottom-3 left-1/2 w-0 h-[3px] -translate-x-1/2 bg-orange-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                @endauth

                @guest
                    <a href="{{ route('auth.check') }}" id="header-login-link" class="relative flex flex-col items-center gap-1 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6 group-hover:text-black transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span class="text-xs font-medium group-hover:text-black transition-colors">Se connecter</span>

                        <span class="absolute -bottom-3 left-1/2 w-0 h-[3px] -translate-x-1/2 bg-orange-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                @endguest
            </nav>



        </div>
    </header>
    <x-alert/>
    @yield('content')
    {{ $slot ?? '' }}
    @stack('modals')

    @if(app()->environment('local'))
        @php
            $devAccounts = [
                ['label' => 'Utilisateur test', 'email' => 'test@example.com'],
                ['label' => 'Super Admin', 'email' => 'super_admin@example.com'],
                ['label' => 'Service Petite Annonce', 'email' => 'service_petite_annonce@example.com'],
                ['label' => 'Directeur Service Petite Annonce', 'email' => 'directeur_service_petite_annonce@example.com'],
                ['label' => 'Service Immobilier', 'email' => 'service_immobilier@example.com'],
                ['label' => 'Directeur Service Immobilier', 'email' => 'directeur_service_immobilier@example.com'],
                ['label' => 'Service Inscription', 'email' => 'service_inscription@example.com'],
                ['label' => 'Directeur Service Inscription', 'email' => 'directeur_service_inscription@example.com'],
                ['label' => 'Service Location', 'email' => 'service_location@example.com'],
                ['label' => 'Directeur Service Location', 'email' => 'directeur_service_location@example.com'],
            ];
        @endphp

        <div class="fixed bottom-1/2 translate-y-1/2 left-6 z-50 text-xs">
            <button id="dev-menu-toggle" type="button" class="px-3 py-2 rounded-lg shadow-md bg-orange-600 text-white font-semibold hover:bg-orange-700 transition">
            Menu dev
            </button>

            <div id="dev-menu-panel" class="hidden mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl">
            <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200">
                <span class="text-sm font-semibold text-gray-800">Menu dev</span>
                <button id="dev-menu-close" type="button" class="text-gray-500 hover:text-gray-700">X</button>
            </div>
            <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                <div class="px-3 py-2">
                    <button type="button" class="flex w-full items-center justify-between text-sm font-semibold text-gray-800" data-dev-collapse="login">
                        Connexions rapides
                        <span class="text-xs text-gray-500" data-dev-arrow="login">▶</span>
                    </button>
                    <div class="mt-2 space-y-0.5 hidden" data-dev-section="login">
                        <form action="/logout" method="POST">
                            @csrf
                            <input type="submit" class="w-full text-left px-3 py-2 hover:bg-orange-50 flex flex-col bg-white border border-gray-100 rounded" value="Se déconnecter">
                        </form>

                        <form method="POST" action="{{ route('dev.login-as-id') }}" class="w-full px-3 py-2 bg-white border border-gray-100 rounded">
                            @csrf
                            <div class="flex items-center gap-2">
                                <input name="idutilisateur" type="number" min="1" inputmode="numeric" placeholder="ID utilisateur" class="w-full px-2 py-1 rounded border border-gray-200 text-xs focus:border-orange-500 focus:ring-orange-500" />
                                <button type="submit" class="px-3 py-1 rounded bg-orange-600 text-white text-xs font-semibold hover:bg-orange-700 transition">OK</button>
                            </div>
                        </form>

                        @foreach($devAccounts as $account)
                        <button type="button" data-dev-login-email="{{ $account['email'] }}" class="w-full text-left px-3 py-2 hover:bg-orange-50 flex flex-col border border-transparent rounded">
                            <span class="text-sm font-semibold text-gray-900">{{ $account['label'] }}</span>
                            <span class="text-[11px] text-gray-500">{{ $account['email'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="px-3 py-2">
                    <button type="button" class="flex w-full items-center justify-between text-sm font-semibold text-gray-800" data-dev-collapse="create">
                        Créer et se connecter
                        <span class="text-xs text-gray-500" data-dev-arrow="create">▶</span>
                    </button>
                    <div class="mt-2 grid grid-cols-2 gap-2 hidden" data-dev-section="create">
                        <button type="button" data-dev-create="particulier" class="px-2 py-2 rounded-md bg-orange-100 text-orange-800 text-xs font-semibold hover:bg-orange-200">Particulier</button>
                        <button type="button" data-dev-create="professionnel" class="px-2 py-2 rounded-md bg-blue-100 text-blue-800 text-xs font-semibold hover:bg-blue-200">Professionnel</button>
                        <button type="button" data-dev-create-unverified="particulier" class="px-2 py-2 rounded-md bg-orange-100 text-orange-800 text-xs font-semibold hover:bg-orange-200">Particulier (non vérifié)</button>
                        <button type="button" data-dev-create-unverified="professionnel" class="px-2 py-2 rounded-md bg-blue-100 text-blue-800 text-xs font-semibold hover:bg-blue-200">Professionnel (non vérifié)</button>
                        <button type="button" data-dev-create-annonce class="px-2 py-2 rounded-md bg-green-100 text-green-800 text-xs font-semibold hover:bg-green-200 col-span-2">Créer une annonce (utilisateur connecté)</button>
                        <button type="button" data-dev-create-cni class="px-2 py-2 rounded-md bg-purple-100 text-purple-800 text-xs font-semibold hover:bg-purple-200 col-span-2">Ajouter une CNI (utilisateur connecté)</button>
                    </div>
                </div>
            </div>
            <div class="px-3 py-2 text-[11px] text-gray-500 border-t border-gray-200">Dev only - se connecter en un clic.</div>
            </div>

            <form id="dev-login-form" method="POST" action="{{ route('dev.login-as') }}" class="hidden">
            @csrf
            <input id="dev-login-email" type="hidden" name="email" value="">
            </form>

            <form id="dev-create-form" method="POST" action="{{ route('dev.create-user') }}" class="hidden">
            @csrf
            <input id="dev-create-type" type="hidden" name="type" value="">
            </form>

            <form id="dev-create-unverified-form" method="POST" action="{{ route('dev.create-user-unverified') }}" class="hidden">
            @csrf
            <input id="dev-create-unverified-type" type="hidden" name="type" value="">
            </form>

            <form id="dev-create-annonce-form" method="POST" action="{{ route('dev.create-annonce') }}" class="hidden">
            @csrf
            </form>

            <form id="dev-create-cni-form" method="POST" action="{{ route('dev.create-cni') }}" class="hidden">
            @csrf
            </form>
        </div>

        <script>
            (() => {
                const toggle = document.getElementById('dev-menu-toggle');
                const panel = document.getElementById('dev-menu-panel');
                const closeBtn = document.getElementById('dev-menu-close');
                const emailInput = document.getElementById('dev-login-email');
                const form = document.getElementById('dev-login-form');
                const createForm = document.getElementById('dev-create-form');
                const createType = document.getElementById('dev-create-type');
                const createUnverifiedForm = document.getElementById('dev-create-unverified-form');
                const createUnverifiedType = document.getElementById('dev-create-unverified-type');
                const createAnnonceForm = document.getElementById('dev-create-annonce-form');
                const createCniForm = document.getElementById('dev-create-cni-form');

                const togglePanel = () => {
                    panel.classList.toggle('hidden');
                };

                toggle?.addEventListener('click', togglePanel);
                closeBtn?.addEventListener('click', togglePanel);

                document.querySelectorAll('[data-dev-login-email]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        emailInput.value = btn.dataset.devLoginEmail || '';
                        form.submit();
                    });
                });

                document.querySelectorAll('[data-dev-create]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        createType.value = btn.dataset.devCreate;
                        createForm.submit();
                    });
                });

                document.querySelectorAll('[data-dev-create-unverified]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        createUnverifiedType.value = btn.dataset.devCreateUnverified;
                        createUnverifiedForm.submit();
                    });
                });

                document.querySelectorAll('[data-dev-create-annonce]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        createAnnonceForm.submit();
                    });
                });

                document.querySelectorAll('[data-dev-create-cni]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        createCniForm.submit();
                    });
                });

                document.querySelectorAll('[data-dev-collapse]').forEach((btn) => {
                    const sectionName = btn.dataset.devCollapse;
                    const section = document.querySelector(`[data-dev-section="${sectionName}"]`);
                    const arrow = document.querySelector(`[data-dev-arrow="${sectionName}"]`);
                    if (!section) return;

                    btn.addEventListener('click', () => {
                        const isHidden = section.classList.toggle('hidden');
                        if (arrow) arrow.textContent = isHidden ? '▶' : '▼';
                    });
                });
            })();
        </script>
    @endif

    @stack('scripts')

    @livewireScripts

    @auth
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const guestFavorites = JSON.parse(localStorage.getItem('guest_favorites') || '[]');
            
            if (guestFavorites.length > 0) {
                fetch('{{ route('user.favorites.sync') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ favorites: guestFavorites })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        localStorage.removeItem('guest_favorites');
                        console.log('Favoris synchronisés avec succès');
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Erreur de synchronisation des favoris:', error));
            }
        });
    </script>
    @endauth

    <div id="chatbot-container" class="fixed bottom-4 right-4 z-50">
        <button id="chatbot-toggle" class="bg-orange-500 hover:bg-orange-600 text-white rounded-full p-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>

        <div id="chatbot-window" class="hidden bg-white rounded-lg shadow-xl w-80 h-96 flex flex-col">
            <div class="bg-orange-500 text-white p-3 rounded-t-lg flex justify-between items-center">
                <span class="font-bold">Assistant Leboncoin</span>
                <button id="chatbot-close" class="text-white hover:text-gray-200">✕</button>
            </div>

            <div id="chatbot-messages" class="flex-1 p-3 overflow-y-auto space-y-2">
                <div class="bg-gray-100 p-2 rounded-lg text-sm">
                Bonjour ! Comment puis-je vous aider ? 
                </div>
            </div>

            <div class="p-3 border-t">
                <div class="flex gap-2">
                    <input type="text" id="chatbot-input" placeholder="Votre message..." class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <button id="chatbot-send" class="bg-orange-500 text-white px-3 py-2 rounded-lg hover:bg-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
    const toggleBtn   = document.querySelector('#chatbot-toggle');
    const chatWindow  = document.querySelector('#chatbot-window');
    const closeBtn    = document.querySelector('#chatbot-close');
    const input       = document.querySelector('#chatbot-input');
    const sendBtn     = document.querySelector('#chatbot-send');
    const messages    = document.querySelector('#chatbot-messages');
    const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.content;

    const addMessage = (text, type = 'bot') => {
        const div = document.createElement('div');
        div.classList.add('p-2','rounded-lg','text-sm');

        if (type === 'user') {
            div.classList.add('bg-orange-100', 'ml-8');
        } else {
            div.classList.add('bg-gray-100');
        }

        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    };

    const showTyping = () => {
        const div = document.createElement('div');
        div.classList.add('bg-gray-100', 'p-2', 'rounded-lg', 'text-sm', 'text-gray-500');
        div.dataset.typing = 'true';
        div.textContent = "En train d'écrire...";
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    };

    const removeTyping = () => {
        messages.querySelector('[data-typing="true"]')?.remove();
    };

    toggleBtn.addEventListener('click', () => {
        chatWindow.classList.toggle('hidden');
        toggleBtn.classList.toggle('hidden');
        input.focus();
    });

    closeBtn.addEventListener('click', () => {
        chatWindow.classList.add('hidden');
        toggleBtn.classList.remove('hidden');
    });

    const sendMessage = async () => {
        const message = input.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        input.value = '';
        showTyping();

        try {
            const response = await fetch('/chatbot-ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {})
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            removeTyping();

            addMessage(
                data.reply ?? "Désolé, je n'ai pas compris. Pouvez-vous reformuler ?",
                'bot'
            );

        } catch (error) {
            removeTyping();
            addMessage("Erreur de connexion au serveur.", 'bot');
        }
    };
    sendBtn.addEventListener('click', sendMessage);

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }
        });
    
    </script>

</body>

</html>
