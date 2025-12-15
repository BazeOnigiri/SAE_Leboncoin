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
            Connectez-vous ou créez votre compte
        </h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('auth.verify') }}">
            @csrf

            <div class="block">
                <label for="email" class="block font-bold text-sm text-gray-700 mb-1">E-mail *</label>
                
                <input id="email" class="block w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 placeholder-gray-400" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Ex: jean.dupont@gmail.com" />

                @error('email') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="w-full bg-[#ec5a13] hover:bg-[#d64d0e] text-white font-bold py-3 px-4 rounded-[18px] transition duration-200 text-center mt-6 shadow-md hover:shadow-lg">
                    {{ __('Continuer') }}
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>