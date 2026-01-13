<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Éditer les annonces</h2>
    </x-slot>

    <div class="bg-[#f8f9fb] min-h-screen pb-12">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <livewire:service-annonce-editor />
        </main>
    </div>
</x-app-layout>