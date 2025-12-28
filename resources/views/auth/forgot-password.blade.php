<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ route('login') }}"
           class="flex items-center gap-2 text-gray-600 hover:text-orange-600 text-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </div>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-12">
        <div class="w-full max-w-xl">
            <x-authentication-card>
                <x-slot name="logo">
                    <x-authentication-card-logo />
                </x-slot>

                <h1 class="text-xl font-bold text-center text-gray-900 mb-2">
                    Mot de passe oublié
                </h1>

                <p class="text-center text-sm text-gray-600 mb-6">
                    Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                </p>

                @session('status')
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ $value }}
                    </div>
                @endsession

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Adresse e-mail *
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Ex : jean.dupont@gmail.com"
                            class="w-full border border-gray-300 rounded-md px-3 py-2
                                   focus:border-[#ec5a13] focus:ring-0"
                        />

                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-[#ec5a13] hover:bg-[#d64d0e]
                               text-white font-semibold py-2.5 rounded-md transition">
                        Envoyer le lien de réinitialisation
                    </button>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>
