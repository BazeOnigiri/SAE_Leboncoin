<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="/" class="flex items-center gap-2 text-gray-600 hover:text-orange-600 font-medium transition">
            <i class="fa-solid fa-arrow-left"></i> 
            <span>Retour à l'accueil</span>
        </a>
    </div>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <h2 class="text-2xl font-bold text-center mb-6 text-gray-900">
            Connectez-vous ou créez votre compte leboncoin
        </h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('auth.verify') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('E-mail *') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Ex: jean.dupont@gmail.com" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out">
                    {{ __('Continuer') }}
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>