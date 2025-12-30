<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="/" class="text-sm text-gray-600 hover:text-orange-600 flex items-center transition">
            ← Retour à l'accueil
        </a>
    </div>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-12">
        <div class="w-full max-w-xl">
            <x-authentication-card>
                <x-slot name="logo">
                    <x-authentication-card-logo />
                </x-slot>

                <div class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-gray-900">
                        Vérification du numéro de téléphone
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Pour sécuriser votre compte, un code a été envoyé au :
                        <br>
                        <span class="font-medium text-gray-900 text-base">
                            {{ auth()->user()->telephoneutilisateur }}
                        </span>
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 text-sm font-medium text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('dev_sms'))
                    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        <p class="text-sm font-semibold text-purple-800 mb-1">🔧 Mode développement - SMS simulé :</p>
                        <p class="text-xs text-purple-700">À : {{ session('dev_sms')['to'] }}</p>
                        <p class="text-sm text-purple-900 font-mono mt-1">{{ session('dev_sms')['message'] }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('phone.verify.verify') }}">
                    @csrf

                    <div>
                        <x-label for="code" value="Code de vérification (6 chiffres)" />

                        <x-input
                            id="code"
                            name="code"
                            type="text"
                            inputmode="numeric"
                            maxlength="6"
                            required
                            autofocus
                            class="mt-1 block w-full text-center tracking-[0.5em] text-2xl font-bold focus:border-orange-600 focus:ring-orange-600"
                            placeholder="••••••"
                        />

                        @error('code')
                            <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <x-button-orange class="w-full justify-center">
                            Vérifier le code
                        </x-button-orange>
                    </div>
                </form>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            Vous n'avez pas reçu le code ?
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('phone.verify.resend') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                        Renvoyer un nouveau code
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-900 underline transition">
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>