<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'leboncoin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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
        <div class="mx-auto h-16 flex items-center justify-between gap-4 max-w-6xl px-6 md:px-12 xl:px-6">

            <a href="/" class="flex-shrink-0">
                <span
                    class="text-orange-lbc font-extrabold text-3xl tracking-tighter text-[#ea580c] rounded-[15px]">leboncoin</span>
            </a>

            <a href="#"
                class="hidden md:flex items-center gap-2 bg-[#ea580c] hover:bg-[#c2410c] text-white transition-colors  font-bold py-2.5 px-5 rounded-xl  shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Déposer une annonce</span>
            </a>

            <div class="hidden lg:flex flex-grow max-w-xl mx-4 relative">
                <form action=""
                    class="w-full flex bg-gray-bg rounded-xl overflow-hidden bg-gray-100 group focus-within:ring-2 ring-orange-lbc/50 transition-all">
                    <input type="text" placeholder="Rechercher sur leboncoin"
                        class="w-full bg-transparent border-none outline-none px-4 py-2.5 text-gray-700 placeholder-gray-500">
                    <button type="submit"
                        class="bg-[#ea580c] hover:bg-[#c2410c] text-white m-1 rounded-[15px] w-10 h-10 flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                </form>
            </div>

            <nav class="flex items-center gap-6 text-gray-700 shrink-0">
                <a href="#" class="hidden lg:flex flex-col items-center gap-1 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span class="text-xs font-medium group-hover:text-black">Mes recherches</span>
                </a>

                <a href="#" class="hidden lg:flex flex-col items-center gap-1 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    <span class="text-xs font-medium group-hover:text-black">Favoris</span>
                </a>

                <a href="#" class="hidden lg:flex flex-col items-center gap-1 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                    <span class="text-xs font-medium group-hover:text-black">Messages</span>
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="hidden lg:flex flex-col items-center gap-1 group">
                        <img class="rounded-full h-6 z-6" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->prenomutilisateur }}">
                        <span class="text-xs font-medium group-hover:text-black">{{ auth()->user()->pseudonyme }}</span>
                    </a>
                @endauth

                @guest
                    <a href="{{ route('auth.check') }}" class="flex flex-col items-center gap-1 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span class="text-xs font-medium group-hover:text-black">Se connecter</span>
                    </a>
                @endguest
            </nav>

        </div>
    </header>
    @yield('content')
    {{ $slot ?? '' }}
    @stack('modals')

    @livewireScripts
</body>

</html>
