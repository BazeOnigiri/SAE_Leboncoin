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

            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

            .snap-x { scroll-snap-type: x mandatory; }
            .snap-start { scroll-snap-align: start; }
        </style>

    </head>
    <body class="bg-[#f8f9fb] font-sans text-gray-900 antialiased">
    <header id="main-header" class="w-full border-b border-gray-200 bg-white py-3 sticky top-0 z-40 left-0 transition-shadow duration-200">
    <div class="mx-auto h-16 flex items-center justify-between gap-4 max-w-6xl px-6 md:px-12 xl:px-6">

            <a href="#" class="flex-shrink-0">
                <span class="text-orange-lbc font-extrabold text-3xl tracking-tighter text-[#ea580c] rounded-[15px]">leboncoin</span>
            </a>

            <a href="#" class="hidden md:flex items-center gap-2 bg-[#ea580c] hover:bg-[#c2410c] text-white transition-colors  font-bold py-2.5 px-5 rounded-xl  shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Déposer une annonce</span>
            </a>

            <div class="hidden lg:flex flex-grow max-w-xl mx-4 relative">
                <form action="" class="w-full flex bg-gray-bg rounded-xl overflow-hidden bg-gray-100 group focus-within:ring-2 ring-orange-lbc/50 transition-all">
                    <input type="text" placeholder="Rechercher sur leboncoin" class="w-full bg-transparent border-none outline-none px-4 py-2.5 text-gray-700 placeholder-gray-500">
                        <button type="submit" class="bg-[#ea580c] hover:bg-[#c2410c] text-white m-1 rounded-[15px] w-10 h-10 flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </button>
                </form>
            </div>
            
            <nav class="flex items-center gap-6 text-gray-700 shrink-0">
            <a href="#" class="hidden lg:flex flex-col items-center gap-1 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <span class="text-xs font-medium group-hover:text-black">Mes recherches</span>
            </a>

            <a href="#" class="hidden lg:flex flex-col items-center gap-1 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
                <span class="text-xs font-medium group-hover:text-black">Favoris</span>
            </a>

            <a href="#" class="hidden lg:flex flex-col items-center gap-1 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                </svg>
                <span class="text-xs font-medium group-hover:text-black">Messages</span>
            </a>

            <a href="#" class="flex flex-col items-center gap-1 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 group-hover:text-black">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span class="text-xs font-medium group-hover:text-black">Se connecter</span>
            </a>
        </nav>

        </div>
    </header>
    <div class="w-full flex justify-center mt-10 mb-12 px-4">
    <div class="flex flex-col items-center w-full mt-10 mb-14">

    <div class="relative z-10 -mb-6 pointer-events-none">
        <svg width="93" height="66" viewBox="0 0 93 66" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[85px] md:w-[93px] h-auto drop-shadow-sm">
            <path d="M32.75 36.04c-2.96 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37 5.36-2.4 5.36-5.37-2.4-5.37-5.36-5.37zm27.46 0c-2.97 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37c2.96 0 5.36-2.4 5.36-5.37s-2.4-5.37-5.36-5.37z" fill="#FDB694"></path>
            <path d="M32.75 32.3c-2.96 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37 5.36-2.4 5.36-5.37c0-2.96-2.4-5.36-5.36-5.36zm27.46 0c-2.97 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37c2.96 0 5.36-2.4 5.36-5.37 0-2.96-2.4-5.36-5.36-5.36z" fill="#2B3444"></path>
            <path d="M67.47 65.25H25.5c-8.2 0-15.08-7.03-15.08-15.24l-.47-19.04c-.38-14.77 12.87-25.63 27.48-23.78l16.83 2.13c4.34.55 7.58 4.26 7.58 8.63v20.79l15.56.92c4.1.24 7.28 3.73 7.12 7.83l-.46 11.76c-.32 8.3-7.98 15-16.6 15.01z" fill="#F4F6F7"></path>
            <path d="M25.5 57.86h41.96c4.76 0 8.62-3.86 8.62-8.63V36.8c0-4.77-3.86-8.63-8.62-8.63H25.5c-4.76 0-8.62 3.86-8.62 8.63v12.43c0 4.77 3.86 8.63 8.62 8.63z" fill="#2B3444"></path>
            <path d="M67.47 65.25H25.5c-8.2 0-15.08-7.03-15.08-15.24l-.47-19.04c-.38-14.77 12.87-25.63 27.48-23.78l16.83 2.13c4.34.55 7.58 4.26 7.58 8.63v20.79l15.56.92c4.1.24 7.28 3.73 7.12 7.83l-.46 11.76c-.32 8.3-7.98 15-16.6 15.01z" fill="#F4F6F7"></path>
            <path d="M58.37 15.38c.3 0 .58-.13.78-.35.2-.22.29-.51.26-.81l-.8-7.32c-.03-.3-.2-.56-.44-.74-.25-.17-.55-.24-.84-.19l-8.09 1.43c-1.24.22-2.06 1.4-1.83 2.65.23 1.24 1.41 2.06 2.66 1.83l5.26-.93.53 4.8c.07.67.63 1.18 1.3 1.18.07 0 1.21 0 1.21 0z" fill="#FDB694"></path>
            <path d="M55.82 8.88s.61 1.31.94 1.68c1.09 1.21 2.92 1.3 4.13.2 1.21-1.09 1.3-2.92.2-4.13-.75-.83-2.16-2.13-2.16-2.13L55.82 8.88z" fill="#FDB694"></path>
            <path d="M76.22 33.47l12.31-2.17c1.24-.22 2.06-1.4 1.84-2.65-.23-1.24-1.4-2.06-2.65-1.83L75.4 28.99c-1.24.22-2.07 1.4-1.84 2.65.22 1.24 1.39 2.07 2.66 1.83z" fill="#FDB694"></path>
            <path d="M22.02 14.3c2.51-1.45 5.54-.4 6.89 2.13 1.3 2.43.48 5.45-1.87 6.9-2.34 1.45-5.39.62-6.86-1.81l-3.78-6.27 5.62-1-.01.05z" fill="#FDB694"></path>
            <path d="M22.02 14.3c2.51-1.45 5.54-.4 6.89 2.13 1.3 2.43.48 5.45-1.87 6.9-2.34 1.45-5.39.62-6.86-1.81l-3.78-6.27 5.62-1-.01.05z" fill="#2B3444"></path>
            <path d="M18.86 14.5c-1.28.74-2.16 1.96-2.45 3.33-1.45-2.4-1.05-5.48 1.23-7.43 2.34-2.01 5.86-1.82 7.95.42l.54.58c-2.61-.03-5.26.95-7.27 3.1z" fill="#FDB694"></path>
            <path d="M14.3 6.14c-2.31 2.01-2.68 5.52-1.05 8.2 1.19-1.6 3.01-2.75 5.08-3.23 1.48-2.63 4.47-4.12 7.61-3.62l.95-2.53C24.55 2.66 20.9 2.37 17.6 4c-.18-1.01.28-2.07 1.21-2.58 1.24-.67 2.79-.21 3.46 1.02l-3.96 2.15c-.31.17-.42.56-.25.87.17.31.56.42.87.25l3.96-2.15c.44.8.41 1.75-.08 2.53 1.09-.52 2.32-.6 3.42-.18l-1.03 2.74c-3.73-.63-7.48 1.06-9.43 4.26-.31-.01-.61.01-.91.06-1.23-2.55-1.32-5.55-.14-8.18l-1.18.64c-1.25.67-1.71 2.22-1.04 3.46.68 1.24 2.23 1.7 3.47 1.03l-1.18.64c-1.33 2.08-1.53 4.7-.46 7.02-.79.41-1.48 1.05-1.97 1.86-.67 1.24-.21 2.79 1.03 3.46l1.19-.64c-1.71-2.45-1.59-5.72.27-8.06.67-.09 1.36-.02 2.03.19-2.01-2.15-4.65-3.14-7.27-3.1l.54-.58c-.2.59-.32 1.21-.33 1.84.22-1.27.97-2.39 2.13-3.1l-.6-.65z" fill="#F4F6F7"></path>
        </svg>
    </div>

    <div class="bg-[#f0f4f7] w-fit mx-auto rounded-[32px] px-10 md:px-16 py-8 text-center relative z-0">
        <h2 class="text-[#5e6a7e] font-bold text-[22px] md:text-[26px] leading-snug">
            Ne passez pas à côté de LA bonne affaire !
        </h2>
    </div>

</div>
</div>
        <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-32">

                <div class="flex items-center gap-3 overflow-x-auto hide-scrollbar pb-4">
        
                    <livewire:search-location />
        
                    <button class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                        <span>Dates</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
        
                    <button class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors" >
                        <span>Voyageurs</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
        
                    <button class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                        <span>Paiement en ligne</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
        
                    
                    <button onclick="openFilters()" class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                        <span>Filtres</span>
                    </button>
                    
                    
                
                    <div id="filter-overlay" onclick="closeFilters()" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity opacity-0"></div>
                
                    <div id="filter-panel" class="fixed inset-y-0 right-0 w-full max-w-[480px] bg-white z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">
                        
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-slate-900">Tous les filtres</h2>
                            <button onclick="closeFilters()" class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                                <span>X</span>
                            </button>
                        </div>
                
                        <div class="flex-1 overflow-y-auto p-6 hide-scrollbar space-y-8">
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-700">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span class="text-lg font-medium text-slate-900">Dates</span>
                                </div>
                
                                <div class="flex gap-4 mb-6">
                                    <div class="flex-1">
                                        <label class="text-xs text-gray-500 block mb-1">Arrivée</label>
                                        <input type="text" placeholder="JJ/MM/AAAA" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                                    </div>
                                    <div class="flex items-center text-sm text-gray-400 pt-5">au</div>
                                    <div class="flex-1">
                                        <label class="text-xs text-gray-500 block mb-1">Départ</label>
                                        <input type="text" placeholder="JJ/MM/AAAA" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                                    </div>
                                </div>
                
                                <div class="bg-white rounded-xl pb-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <button class="text-gray-400 hover:text-gray-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></button>
                                        <span class="font-bold text-slate-900">novembre 2025</span>
                                        <button class="text-slate-900"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></button>
                                    </div>
                <!-- Calendar -->
                                    <div class="grid grid-cols-7 text-center mb-2">
                                        <span class="text-xs text-gray-400">lu</span>
                                        <span class="text-xs text-gray-400">ma</span>
                                        <span class="text-xs text-gray-400">me</span>
                                        <span class="text-xs text-gray-400">je</span>
                                        <span class="text-xs text-gray-400">ve</span>
                                        <span class="text-xs text-gray-400">sa</span>
                                        <span class="text-xs text-gray-400">di</span>
                                    </div>
                
                                    <div class="grid grid-cols-7 text-center gap-y-4 text-sm font-medium text-slate-500">
                                        <span></span><span></span><span></span><span></span><span></span>
                                            <span class="text-blue-600 hover:bg-blue-50 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">1</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">2</span>

                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">3</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">4</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">5</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">6</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">7</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">8</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">9</span>

                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">10</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">11</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">12</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">13</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">14</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">15</span>
                                            <span class="hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center mx-auto cursor-pointer">16</span>
                                    </div>
                                </div>
                                <hr class="border-gray-200 mt-2">
                            </div>
                
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-700">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <span class="text-lg font-medium text-slate-900">Voyageurs</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-900">Nombre de voyageurs</span>
                                    <div class="flex items-center gap-4">
                                        <button class="w-8 h-8 rounded-full border border-gray-400 text-gray-400 flex items-center justify-center hover:border-gray-600 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        </button>
                                        <span class="w-4 text-center text-slate-900 font-medium">1</span>
                                        <button class="w-8 h-8 rounded-full border border-slate-900 text-slate-900 flex items-center justify-center hover:bg-gray-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <hr class="border-gray-200 mt-6">
                            </div>
                
                                <div class="flex items-center justify-between pb-6">
                                    <div class="flex items-center gap-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                                        </label>
                                        <span class="text-lg font-medium text-slate-900">Paiement en ligne</span>
                                    </div>
                                </div>
                                    <p class="text-sm text-gray-500 mb-2">Identifiez facilement les annonces réservables en ligne</p>
                                    <p class="text-xs text-gray-400 leading-relaxed">
                                        Pour les hôtels, voir les modalités de paiement sur le site partenaire. Une taxe de séjour et des frais de service s'appliquent...
                                    </p>
                
                                    <div class="flex-1 overflow-y-auto px-6 py-2 hide-scrollbar"></div>

                        <div class="py-6 border-b border-gray-200 cursor-pointer group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-start gap-4">
                                    <div class="pt-1 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-lg font-medium text-slate-800 group-hover:underline decoration-1 underline-offset-2">Type d’hébergement</div>
                                        <div class="text-sm text-gray-500 mt-1">Appartement, Bateau, Bungalow, Bus, ...</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between items-center text-slate-600">
                                <span>Tout</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-800">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </div>

                        <div class="py-6 border-b border-gray-200">
                            <div class="flex items-center gap-4 mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
                                    </svg>
                                <span class="text-lg font-medium text-slate-800">Nature du logement</span>
                            </div>

                            <div class="space-y-5">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" class="custom-checkbox">
                                    <span class="ml-4 text-lg text-slate-700 flex-1">Location ou Gîte</span>
                                    <span class="text-sm text-gray-400 tabular-nums">227 007</span>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" class="custom-checkbox">
                                    <span class="ml-4 text-lg text-slate-700 flex-1">Chambre d'hôtes</span>
                                    <span class="text-sm text-gray-400 tabular-nums">12 868</span>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" class="custom-checkbox">
                                    <span class="ml-4 text-lg text-slate-700 flex-1">Camping</span>
                                    <span class="text-sm text-gray-400 tabular-nums">18 988</span>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" class="custom-checkbox">
                                    <span class="ml-4 text-lg text-slate-700 flex-1">Hébergement insolite</span>
                                    <span class="text-sm text-gray-400 tabular-nums">6 871</span>
                                </label>
                            </div>
                        </div>

                        <div class="py-6 border-b border-gray-200">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-6 h-6 rounded-full border border-slate-600 flex items-center justify-center text-slate-600 font-serif italic text-sm font-bold">€</div>
                                <span class="text-lg font-medium text-slate-800">Prix par nuit</span>
                            </div>

                            <div class="flex items-center gap-4 mb-2">
                                <div class="flex-1">
                                    <label class="text-slate-800 text-base mb-2 block">Minimum</label>
                                    <div class="flex items-center border border-slate-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-slate-900 focus-within:border-slate-900 h-12">
                                        <input type="number" class="w-full h-full px-4 outline-none text-slate-900 placeholder-transparent" placeholder="Min">
                                        <div class="h-full px-4 border-l border-slate-300 bg-white flex items-center text-slate-900">€</div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="text-slate-800 text-base mb-2 block">Maximum</label>
                                    <div class="flex items-center border border-slate-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-slate-900 focus-within:border-slate-900 h-12">
                                        <input type="number" class="w-full h-full px-4 outline-none text-slate-900 placeholder-transparent" placeholder="Max">
                                        <div class="h-full px-4 border-l border-slate-300 bg-white flex items-center text-slate-900">€</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>

                        
                
                        <div class="p-4 border-t border-gray-200 flex items-center justify-between bg-white">
                            <button class="text-slate-900 font-medium px-4 py-2 hover:underline decoration-1 underline-offset-4">
                                Tout Effacer
                            </button>
                            <button class="bg-[#ea580c] hover:bg-[#c2410c] text-white transition-colors font-bold py-3 px-6 rounded-xl ">
                                Rechercher (inserer nb annonces)
                            </button>
                        </div>
                        </div>
                </div>
                <div class="w-full overflow-x-auto no-scrollbar py-2">
                <div class="flex flex-wrap gap-3 font-sans">
                <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-blue-50 border border-slate-700 rounded-xl hover:bg-blue-100 transition-colors">
                    <span>Locations saisonnières</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-blue-50 border border-slate-700 rounded-xl hover:bg-blue-100 transition-colors">
                    <span>Tri : Pertinence</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
</div>
            </div>

            <div class="mt-8">
                <livewire:annonce-list />
            </div>
        </div>
    </body>
</html>
