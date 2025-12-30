<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-12">
        <div class="w-full max-w-xl">
            <x-authentication-card>
                <x-slot name="logo">
                    <x-authentication-card-logo />
                </x-slot>

                <h1 class="text-xl font-bold text-center text-gray-900 mb-2">
                    Vérifiez votre adresse e-mail
                </h1>

                <p class="text-center text-sm text-gray-600 mb-6">
                    Merci de votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ?
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200 flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-sm font-medium text-green-800">
                            Un nouveau lien de vérification a été envoyé à l'adresse e-mail fournie lors de l'inscription.
                        </span>
                    </div>
                @endif

                <div class="mt-4 flex flex-col gap-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-button-orange class="w-full justify-center">
                            Renvoyer l'e-mail de vérification
                        </x-button-orange>
                    </form>

                    <div class="flex items-center justify-between text-sm mt-2">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 underline underline-offset-2">
                            Retour à l'accueil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 underline underline-offset-2 ml-4">
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>

            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>