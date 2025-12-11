<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier mon profil</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('update-profile-information')
                @endif
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}" class="text-orange-600 font-bold hover:underline">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>