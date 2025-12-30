<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="/" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
            ← Retour à l'accueil
        </a>
    </div>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">
        <div class="w-full max-w-xl">
            <x-authentication-card>
                <x-slot name="logo">
                    <x-authentication-card-logo />
                </x-slot>

                <h2 class="text-lg font-semibold text-center mb-6 text-gray-900">
                    Connexion / Création de compte
                </h2>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('auth.verify') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="email" value="E-mail *" />

                        <x-input 
                            id="email" 
                            class="block mt-1 w-full focus:border-orange-600 focus:ring-orange-600" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                            placeholder="Ex : jean.dupont@gmail.com" 
                        />
                        
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-button-orange class="w-full">
                            Continuer
                        </x-button-orange>
                    </div>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>