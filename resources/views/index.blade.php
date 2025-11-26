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
    <div class="container mx-auto p-7 px-4 max-w-[1280px] h-16 flex items-center justify-between gap-4">

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
                <form action="" class="w-full flex bg-gray-bg rounded-xl overflow-hidden group focus-within:ring-2 ring-orange-lbc/50 transition-all">
                    <input type="text" 
                        placeholder="Rechercher sur leboncoin" 
                        class="w-full bg-transparent border-none outline-none px-4 py-2.5 text-gray-700 placeholder-gray-500">
                    
                        <button type="submit" class="bg-[#ea580c] hover:bg-[#c2410c] text-white m-1 rounded-[15px] w-10 h-10 flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </button>
                </form>
            </div>

            <nav class="flex items-center gap-6">
                
                <a href="#" class="hidden xl:flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors group">
                    <div class="relative">
                        <i class="fa-regular fa-bell text-xl"></i>
                    </div>
                    <span class="text-xs font-medium">Mes recherches</span>
                </a>

                <a href="#" class="hidden xl:flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors">
                    <i class="fa-regular fa-heart text-xl"></i>
                    <span class="text-xs font-medium">Favoris</span>
                </a>

                <a href="#" class="hidden xl:flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors">
                    <i class="fa-regular fa-comment-dots text-xl"></i>
                    <span class="text-xs font-medium">Messages</span>
                </a>

                <a {{ route('login') }} class="flex flex-col items-center gap-1 text-gray-700 hover:text-black transition-colors ml-2">
                    <i class="fa-regular fa-user text-xl"></i>
                    <span class="text-xs font-medium">Se connecter</span>
                </a>
            </nav>

        </div>
    </header>
        <div class=" bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-20">
  
                <div class="flex items-center gap-3 overflow-x-auto hide-scrollbar pb-4">
        
                    <button class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
        
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-800">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        
                        <span class="text-slate-900">Choisir une destination</span>
                    
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    
                    </button>
        
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
                                <h3 class="text-base text-gray-500 mb-2">Catégories</h3>
                                <div class="flex justify-between items-center py-2 cursor-pointer group">
                                    <span class="text-lg text-slate-900">Locations saisonnières</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400 group-hover:text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </div>
                                <hr class="border-gray-200 mt-2">
                            </div>
                
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
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
            <h1 class=" text-xl font-bold mb-6">Annonces Location vacances</h1>
            <p class=" mb-6 font-bold">{{ $annonces->count() }} annonces</p>
            @foreach ($annonces as $annonce)
                <div class="flex mb-2.5">
                    <div class="relative w-80 h-56 mr-4">
                        <button
                            onclick="scrollLeft{{ $annonce->idannonce }}()"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10">
                            ‹
                        </button>
                    
                        <button
                            onclick="scrollRight{{ $annonce->idannonce }}()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center z-10">
                            ›
                        </button>
                    
                        <div
                            id="carousel{{ $annonce->idannonce }}"
                            class="w-full h-full overflow-x-auto flex gap-2 rounded-3xl scroll-smooth
                                snap-x snap-mandatory scrollbar-hide">
                            @foreach ($annonce->photos ?? [] as $photo)
                                <div class="min-w-full h-full snap-start rounded-3xl overflow-hidden">
                                    <img src="{{ $photo->lienphoto }}" loading="lazy" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    
                        <div id="dots{{ $annonce->idannonce }}" class="absolute bottom-1 w-full flex justify-center gap-2">
                            @foreach ($annonce->photos ?? [] as $i => $photo)
                                <div class="dot{{ $annonce->idannonce }} w-2 h-2 rounded-full bg-white/50 transition-all duration-300"></div>
                            @endforeach
                        </div>
                    </div>

                    <script>
                        const carousel{{ $annonce->idannonce }} = document.getElementById("carousel{{ $annonce->idannonce }}");
                        const dots{{ $annonce->idannonce }} = document.querySelectorAll(".dot{{ $annonce->idannonce }}");
                        const total{{ $annonce->idannonce }} = dots{{ $annonce->idannonce }}.length;
                    
                        function scrollRight{{ $annonce->idannonce }}() {
                            let el = carousel{{ $annonce->idannonce }};
                            if (el.scrollLeft + el.clientWidth >= el.scrollWidth - 5) {
                                el.scrollTo({ left: 0, behavior: "smooth" });
                            } else {
                                el.scrollBy({ left: el.clientWidth, behavior: "smooth" });
                            }
                        }
                    
                        function scrollLeft{{ $annonce->idannonce }}() {
                            let el = carousel{{ $annonce->idannonce }};
                            if (el.scrollLeft <= 5) {
                                el.scrollTo({ left: el.scrollWidth, behavior: "smooth" });
                            } else {
                                el.scrollBy({ left: -el.clientWidth, behavior: "smooth" });
                            }
                        }
                    
                        carousel{{ $annonce->idannonce }}.addEventListener("scroll", () => {
                            let el = carousel{{ $annonce->idannonce }};
                            let index = Math.round(el.scrollLeft / el.clientWidth);
                        
                            dots{{ $annonce->idannonce }}.forEach((dot, i) => {
                                if (i === index) {
                                    dot.classList.add("w-3", "h-3", "bg-white");
                                    dot.classList.remove("bg-white/50", "w-2", "h-2");
                                } else {
                                    dot.classList.remove("w-3", "h-3", "bg-white");
                                    dot.classList.add("bg-white/50", "w-2", "h-2");
                                }
                            });
                        });
                    
                        dots{{ $annonce->idannonce }}[0].classList.add("w-3", "h-3", "bg-white");
                    </script>

                    <div class="flex h-auto flex-col justify-between">
                        <div>
                            <div class="gap-x-sm flex items-center justify-between w-80">
                                <h2 class=" font-black">{{ $annonce->titreannonce }}</h2>
                                <div class="flex">
                                    <svg class=" mr-0.5" style="fill: #b84a14; height: 18px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-title="SvgStarFill" fill="currentColor" class="fill-current shrink-0 text-current w-sz-16 h-sz-16 mr-sm" data-spark-component="icon" aria-hidden="true" focusable="false"><path d="M11.0437 2.29647C10.7942 2.46286 10.5447 2.71245 10.3784 3.04524L8.29938 7.6211L3.55925 8.28668C3.22661 8.28668 2.97713 8.45307 2.64449 8.61947C2.39501 8.86906 2.14553 9.11865 2.06237 9.45144C1.97921 9.86742 1.97921 10.2002 2.06237 10.533C2.14553 10.8658 2.31185 11.1986 2.56133 11.4482L6.05405 14.9425L5.22245 19.9343C5.13929 20.2671 5.22245 20.5999 5.30561 20.9327C5.38877 21.2655 5.63825 21.5151 5.88773 21.6814C6.13721 21.8478 6.46985 22.0142 6.8025 22.0142C7.13514 22.0142 7.46778 21.931 7.71726 21.8478L12.0416 19.5183L16.3659 21.8478C16.6154 22.0142 16.948 22.0974 17.2807 22.0142C17.6133 22.0142 17.9459 21.8478 18.1954 21.6814C18.4449 21.5151 18.6944 21.1823 18.7775 20.9327C18.8607 20.5999 18.9439 20.2671 18.8607 19.9343L18.0291 14.9425L21.4387 11.365C21.6882 11.1154 21.8545 10.8658 21.9376 10.533C22.0208 10.2002 22.0208 9.86742 21.9376 9.53464C21.8545 9.20185 21.6882 8.95225 21.4387 8.70266C21.1892 8.45307 20.8566 8.36987 20.6071 8.28668L15.8669 7.5379L13.7048 3.04524C13.5385 2.71245 13.3721 2.46286 13.0395 2.29647C12.79 2.13007 12.5405 2.04688 12.2911 2.04688H11.9584C11.5426 2.13007 11.2931 2.21327 11.0437 2.29647Z"></path></svg>
                                    <span class=" text-sm opacity-75">{{ $annonce->nombreetoilesleboncoin }}</span>
                                </div>
                            </div>
                            <p>{{ $annonce->nombreetoilesleboncoin }} / 5</p>
                            <div class=" text-sm">
                                @php $totalPlaces = 0; @endphp
                                @foreach ($annonce->chambres ?? [] as $chambre)
                                    @php
                                        $cap = $chambre->capacitechambre ?? $chambre->capacite_chambre ?? null;
                                        $totalPlaces += (int) ($cap ?? 0);
                                    @endphp
                                @endforeach
                                {{ $totalPlaces ?? '?' }} pers.
                                <span class="mx-sm text-neutral inline-block font-bold">·</span>
                                {{ $annonce->typehebergement->nomtypehebergement }}
                            </div>
                        </div>
                        <div>
                            <p class=" font-bold text-sm">
                                @php
                                    $price = number_format($annonce->prixnuitee, 2, '.', '');
                                    $price = preg_replace('/\.00$/', '', $price);
                                @endphp
                                <small>à partir de </small>{{ $price }} € <small> / nuit</small>
                            </p>
                            <p class=" text-xs opacity-75">{{ $annonce->adresse->ville->nomville }} {{ $annonce->adresse->ville->codepostal }}</p>
                        </div>
                    </div>
                </div>
                <hr class="my-6 opacity-50">
            @endforeach
        </div>
    </body>
</html>
