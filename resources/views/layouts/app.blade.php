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

    @livewireScripts
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
        <div class="container mx-auto p-7 px-4 max-w-[1280px] h-16 flex items-center justify-between gap-4">

            <a href="#" class="flex-shrink-0">
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
                    class="w-full flex bg-gray-bg rounded-xl overflow-hidden group focus-within:ring-2 ring-orange-lbc/50 transition-all">
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

            <nav class="flex items-center gap-6">

                <a href="#"
                    class="hidden xl:flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors group">
                    <div class="relative">
                        <i class="fa-regular fa-bell text-xl"></i>
                    </div>
                    <span class="text-xs font-medium">Mes recherches</span>
                </a>

                <a href="#"
                    class="hidden xl:flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors">
                    <i class="fa-regular fa-heart text-xl"></i>
                    <span class="text-xs font-medium">Favoris</span>
                </a>

                <a href="#"
                    class="hidden xl:flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors">
                    <i class="fa-regular fa-comment-dots text-xl"></i>
                    <span class="text-xs font-medium">Messages</span>
                </a>

                <a {{ route('login') }}
                    class="flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors ml-2">
                    <i class="fa-regular fa-user text-xl"></i>
                    <span class="text-xs font-medium">Se connecter</span>
                </a>
            </nav>

        </div>
    </header>
    <div class=" bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-20">
        @yield('content')
    </div>
</body>

</html>
