<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6 text-center">
            <h2 class="text-xl font-semibold text-gray-900">
                Vérification du numéro de téléphone
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Un code de vérification a été envoyé au numéro :
                <span class="font-medium text-gray-800">
                    {{ auth()->user()->telephoneutilisateur }}
                </span>
            </p>
        </div>

        {{-- Messages --}}
        @if (session('success'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif

        {{-- SMS simulé (dev) --}}
        @if (session('dev_sms'))
            <div class="mb-4 p-3 rounded-md bg-purple-50 border border-purple-200 text-sm">
                <p class="font-semibold text-purple-700 mb-1">
                    Mode développement – SMS simulé
                </p>
                <p class="text-xs text-gray-600">
                    À : {{ session('dev_sms.to') }}
                </p>
                <p class="mt-1 font-mono text-gray-800">
                    {{ session('dev_sms.message') }}
                </p>
            </div>
        @endif

        {{-- Formulaire code --}}
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
                    class="mt-1 block w-full text-center tracking-widest text-lg"
                    placeholder="000000"
                />

                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <x-button class="w-full justify-center">
                    Vérifier le code
                </x-button>
            </div>
        </form>

        {{-- Séparateur --}}
        <div class="my-6 border-t border-gray-200"></div>

        {{-- Renvoyer --}}
        <form method="POST" action="{{ route('phone.verify.resend') }}">
            @csrf
            <x-button type="submit" class="w-full justify-center" color="secondary">
                Renvoyer un nouveau code
            </x-button>
        </form>

        {{-- Logout --}}
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Se déconnecter
                </button>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
