<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 bg-gray-100">
        <div class="w-full max-w-md bg-white border border-gray-200 rounded-md p-6">
            <div class="flex items-center gap-3 mb-4">
                <img src="/assets/Leboncoin_logo.svg" class="h-6" alt="leboncoin" />
                <span class="text-sm font-semibold text-gray-700">Vérification e-mail</span>
            </div>

            <h1 class="text-lg font-semibold text-gray-900 mb-2">Vérifiez votre adresse e-mail</h1>

            <p class="text-sm text-gray-600 mb-4">
                Un e-mail de vérification vient de vous être envoyé.
                Cliquez sur le lien reçu pour activer votre compte.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 border border-green-300 bg-green-50 text-green-800 text-sm px-3 py-2 rounded">
                    Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <x-button type="submit"class="w-full justify-center">
                    Renvoyer l’e-mail
                </x-button>
            </form>

            <div class="flex items-center justify-between text-sm">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-gray-900 underline">
                        Se déconnecter
                    </button>
                </form>

                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 underline">
                    Retour connexion
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
