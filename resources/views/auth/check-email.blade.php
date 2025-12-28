<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="/" class="text-sm text-gray-600 hover:text-gray-900">
            ← Retour à l'accueil
        </a>
    </div>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">
        <div class="w-full max-w-xl">
            <x-authentication-card>
                <x-slot name="logo">
                    <x-authentication-card-logo />
                </x-slot>

                <h2 class="text-lg font-semibold text-center mb-4 text-gray-900">
                    Connexion / Création de compte
                </h2>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('auth.verify') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>

                        <input
                            id="email"
                            class="block w-full border border-gray-300 rounded py-2 px-3 focus:border-orange-600 focus:ring-0 placeholder-gray-400"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="Ex : jean.dupont@gmail.com"
                        />

                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded">
                        Continuer
                    </button>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>
