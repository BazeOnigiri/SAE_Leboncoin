@extends('layouts.app')
@section('content')
    <div class="w-full flex justify-center mt-10 mb-12 px-4">
        <div class="flex flex-col items-center w-full mt-10 mb-14">

            <div class="relative z-10 -mb-6 pointer-events-none">
                <svg width="93" height="66" viewBox="0 0 93 66" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-[85px] md:w-[93px] h-auto drop-shadow-sm">
                    <path
                        d="M32.75 36.04c-2.96 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37 5.36-2.4 5.36-5.37-2.4-5.37-5.36-5.37zm27.46 0c-2.97 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37c2.96 0 5.36-2.4 5.36-5.37s-2.4-5.37-5.36-5.37z"
                        fill="#FDB694"></path>
                    <path
                        d="M32.75 32.3c-2.96 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37 5.36-2.4 5.36-5.37c0-2.96-2.4-5.36-5.36-5.36zm27.46 0c-2.97 0-5.37 2.4-5.37 5.37s2.4 5.37 5.37 5.37c2.96 0 5.36-2.4 5.36-5.37 0-2.96-2.4-5.36-5.36-5.36z"
                        fill="#2B3444"></path>
                    <path
                        d="M67.47 65.25H25.5c-8.2 0-15.08-7.03-15.08-15.24l-.47-19.04c-.38-14.77 12.87-25.63 27.48-23.78l16.83 2.13c4.34.55 7.58 4.26 7.58 8.63v20.79l15.56.92c4.1.24 7.28 3.73 7.12 7.83l-.46 11.76c-.32 8.3-7.98 15-16.6 15.01z"
                        fill="#F4F6F7"></path>
                    <path
                        d="M25.5 57.86h41.96c4.76 0 8.62-3.86 8.62-8.63V36.8c0-4.77-3.86-8.63-8.62-8.63H25.5c-4.76 0-8.62 3.86-8.62 8.63v12.43c0 4.77 3.86 8.63 8.62 8.63z"
                        fill="#2B3444"></path>
                    <path
                        d="M67.47 65.25H25.5c-8.2 0-15.08-7.03-15.08-15.24l-.47-19.04c-.38-14.77 12.87-25.63 27.48-23.78l16.83 2.13c4.34.55 7.58 4.26 7.58 8.63v20.79l15.56.92c4.1.24 7.28 3.73 7.12 7.83l-.46 11.76c-.32 8.3-7.98 15-16.6 15.01z"
                        fill="#F4F6F7"></path>
                    <path
                        d="M58.37 15.38c.3 0 .58-.13.78-.35.2-.22.29-.51.26-.81l-.8-7.32c-.03-.3-.2-.56-.44-.74-.25-.17-.55-.24-.84-.19l-8.09 1.43c-1.24.22-2.06 1.4-1.83 2.65.23 1.24 1.41 2.06 2.66 1.83l5.26-.93.53 4.8c.07.67.63 1.18 1.3 1.18.07 0 1.21 0 1.21 0z"
                        fill="#FDB694"></path>
                    <path
                        d="M55.82 8.88s.61 1.31.94 1.68c1.09 1.21 2.92 1.3 4.13.2 1.21-1.09 1.3-2.92.2-4.13-.75-.83-2.16-2.13-2.16-2.13L55.82 8.88z"
                        fill="#FDB694"></path>
                    <path
                        d="M76.22 33.47l12.31-2.17c1.24-.22 2.06-1.4 1.84-2.65-.23-1.24-1.4-2.06-2.65-1.83L75.4 28.99c-1.24.22-2.07 1.4-1.84 2.65.22 1.24 1.39 2.07 2.66 1.83z"
                        fill="#FDB694"></path>
                    <path
                        d="M22.02 14.3c2.51-1.45 5.54-.4 6.89 2.13 1.3 2.43.48 5.45-1.87 6.9-2.34 1.45-5.39.62-6.86-1.81l-3.78-6.27 5.62-1-.01.05z"
                        fill="#FDB694"></path>
                    <path
                        d="M22.02 14.3c2.51-1.45 5.54-.4 6.89 2.13 1.3 2.43.48 5.45-1.87 6.9-2.34 1.45-5.39.62-6.86-1.81l-3.78-6.27 5.62-1-.01.05z"
                        fill="#2B3444"></path>
                    <path
                        d="M18.86 14.5c-1.28.74-2.16 1.96-2.45 3.33-1.45-2.4-1.05-5.48 1.23-7.43 2.34-2.01 5.86-1.82 7.95.42l.54.58c-2.61-.03-5.26.95-7.27 3.1z"
                        fill="#FDB694"></path>
                    <path
                        d="M14.3 6.14c-2.31 2.01-2.68 5.52-1.05 8.2 1.19-1.6 3.01-2.75 5.08-3.23 1.48-2.63 4.47-4.12 7.61-3.62l.95-2.53C24.55 2.66 20.9 2.37 17.6 4c-.18-1.01.28-2.07 1.21-2.58 1.24-.67 2.79-.21 3.46 1.02l-3.96 2.15c-.31.17-.42.56-.25.87.17.31.56.42.87.25l3.96-2.15c.44.8.41 1.75-.08 2.53 1.09-.52 2.32-.6 3.42-.18l-1.03 2.74c-3.73-.63-7.48 1.06-9.43 4.26-.31-.01-.61.01-.91.06-1.23-2.55-1.32-5.55-.14-8.18l-1.18.64c-1.25.67-1.71 2.22-1.04 3.46.68 1.24 2.23 1.7 3.47 1.03l-1.18.64c-1.33 2.08-1.53 4.7-.46 7.02-.79.41-1.48 1.05-1.97 1.86-.67 1.24-.21 2.79 1.03 3.46l1.19-.64c-1.71-2.45-1.59-5.72.27-8.06.67-.09 1.36-.02 2.03.19-2.01-2.15-4.65-3.14-7.27-3.1l.54-.58c-.2.59-.32 1.21-.33 1.84.22-1.27.97-2.39 2.13-3.1l-.6-.65z"
                        fill="#F4F6F7"></path>
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

            <button onclick="openFilters()"
                class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                <span>Dates</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-4 h-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <button onclick="openFilters()"
                class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                <span>Voyageurs</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-4 h-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <button onclick="openFilters()"
                class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                <span>Paiement en ligne</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-4 h-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>


            <button onclick="openFilters()"
                class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 text-slate-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                </svg>
                <span>Filtres</span>
            </button>



            <div id="filter-overlay" onclick="closeFilters()"
                class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity opacity-0">
            </div>

            <div id="filter-panel"
                class="fixed inset-y-0 right-0 w-full max-w-[480px] bg-white z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">
                <div x-data="{ sidebarOpen: false }">
                    <div class="flex items-center gap-3 overflow-x-auto hide-scrollbar pb-4">

                        <button @click="sidebarOpen = true"
                            class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-5 h-5 text-slate-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                            <span>Filtres</span>
                        </button>

                        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
                            class="fixed inset-0 bg-black/50 z-40 transition-opacity" style="display: none;">
                        </div>

                        <div id="filter-panel" :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'"
                            class="fixed inset-y-0 right-0 w-full max-w-[480px] bg-white z-50 transform transition-transform duration-300 ease-in-out shadow-2xl">
                            <livewire:filter-sidebar />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full overflow-x-auto no-scrollbar py-2">
            <div class="flex flex-wrap gap-3 font-sans">
                <button
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-blue-50 border border-slate-700 rounded-xl hover:bg-blue-100 transition-colors">
                    <span>Locations saisonnières</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
                <button
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-blue-50 border border-slate-700 rounded-xl hover:bg-blue-100 transition-colors">
                    <span>Tri : Pertinence</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="mt-8">
            <livewire:annonce-list />
        </div>
    </div>
@endsection
