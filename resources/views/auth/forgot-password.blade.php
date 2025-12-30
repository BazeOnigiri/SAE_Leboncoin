<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center transition">
            ←Retour
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

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 px-4 py-3 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="email" value="Adresse e-mail *" />

                        <x-input 
                            id="email" 
                            class="block mt-1 w-full focus:border-orange-600 focus:ring-orange-600" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                            autocomplete="username" 
                            placeholder="Ex : jean.dupont@gmail.com" 
                        />

                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <div>
                        <x-button-orange class="w-full justify-center">
                            Envoyer le lien de réinitialisation
                        </x-button-orange>
                    </div>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>