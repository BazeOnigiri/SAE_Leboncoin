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
                    Réinitialiser le mot de passe
                </h1>

                <p class="text-center text-sm text-gray-600 mb-6">
                    Choisissez un nouveau mot de passe pour sécuriser votre compte.
                </p>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Adresse e-mail
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full border border-gray-300 rounded-md px-3 py-2
                                   bg-gray-100 text-gray-600 cursor-not-allowed"
                            readonly
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Nouveau mot de passe *
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="w-full border border-gray-300 rounded-md px-3 py-2
                                   focus:border-[#ec5a13] focus:ring-0"
                        />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmer le mot de passe *
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="w-full border border-gray-300 rounded-md px-3 py-2
                                   focus:border-[#ec5a13] focus:ring-0"
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-[#ec5a13] hover:bg-[#d64d0e]
                               text-white font-semibold py-2.5 rounded-md transition">
                        Réinitialiser le mot de passe
                    </button>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>
