<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Édition de l'annonce #{{ $annonce->idannonce }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f8f9fb]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:service-annonce-edit-form :annonce="$annonce" />
        </div>
    </div>
</x-app-layout>