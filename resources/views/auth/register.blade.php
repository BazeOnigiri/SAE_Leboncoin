<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <h1 class="text-xl font-semibold mb-4">
            Créez un compte
        </h1>
        <p class="mb-6 text-sm text-gray-600">
            Bénéficiez d’une expérience personnalisée avec les annonces de vacances.
        </p>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register.email') }}">
            @csrf

            {{-- E-mail --}}
            <div>
                <x-label for="email" value="E-mail *" />
                <x-input id="email"
                         class="block mt-1 w-full"
                         type="email"
                         name="email"
                         :value="old('email')"
                         required
                         autofocus
                         autocomplete="username" />
            </div>

            {{-- Mot de passe --}}
            <div class="mt-4">
                <x-label for="password" value="Mot de passe *" />
                <x-input id="password"
                         class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required
                         autocomplete="new-password" />
            </div>

            {{-- Confirmation mot de passe --}}
            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirmer le mot de passe *" />
                <x-input id="password_confirmation"
                         class="block mt-1 w-full"
                         type="password"
                         name="password_confirmation"
                         required
                         autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-button class="ms-4">
                    Continuer
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
