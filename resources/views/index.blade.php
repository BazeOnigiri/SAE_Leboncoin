@extends('layouts.app')
@section('content')
    <div class="w-full flex justify-center mt-12 mb-12 px-4">
        <div class="flex flex-col items-center w-full">
            <div class="bg-[#f0f4f7] w-fit mx-auto rounded-[32px] px-10 md:px-16 py-8 text-center relative z-0">
                <h2 class="text-[#5e6a7e] font-bold text-[22px] md:text-[26px] leading-snug">
                    Ne passez pas à côté de LA bonne affaire !
                </h2>
            </div>

        </div>
    </div>
    <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6">

        <div x-data="{ 
            sidebarOpen: false,
            init() {
                this.$watch('sidebarOpen', value => {
                    const botman = document.getElementById('botmanWidgetRoot');
                    if (botman) {
                        botman.style.display = value ? 'none' : 'block';
                    }
                });
            }
        }" @close-filter-panel.window="sidebarOpen = false">

            <div class="flex items-center gap-3 overflow-x-auto hide-scrollbar pb-4">

                <livewire:search-location />

                <button @click="sidebarOpen = true"
                    class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                    <span>Dates</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-4 h-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <button @click="sidebarOpen = true"
                    class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                    <span>Prix</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-4 h-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <button @click="sidebarOpen = true"
                    class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                    <span>Chambres</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-4 h-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <button @click="sidebarOpen = true" id="filter-btn"
                    class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5 text-slate-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                    <span>Filtres</span>
                </button>

            </div>

            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
                class="fixed inset-0 bg-black/50 z-40" style="display: none;">
            </div>

            <div x-show="sidebarOpen" id="filter-panel" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="fixed inset-y-0 right-0 w-full max-w-[480px] bg-white z-50 shadow-2xl" style="display: none;">

                <livewire:filter-sidebar />

            </div>

        </div>

        <div class="mt-8">
            <livewire:annonce-list />
        </div>

    </div>
@endsection