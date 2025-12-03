<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Connexion et sécurité</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Connexion et sécurité</h1>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    @livewire('profile.update-password-form')
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    @livewire('profile.two-factor-authentication-form')
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            <div class="mt-12 mb-12">
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    @livewire('profile.delete-user-form')
                @endif
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>