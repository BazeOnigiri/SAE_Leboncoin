<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ route('auth.check') }}" class="text-sm text-gray-600 hover:text-gray-900">
            ← Retour
        </a>
    </div>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">
        <div class="w-full max-w-xl">
            <x-authentication-card>
                <x-slot name="logo">
                    <x-authentication-card-logo />
                </x-slot>

                <h1 class="text-lg font-semibold text-center text-gray-900 mb-1">
                    Connexion
                </h1>
                <p class="text-center text-sm text-gray-600 mb-6">
                    Connectez-vous pour accéder à votre espace.
                </p>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 text-sm text-green-800 bg-green-50 border border-green-200 px-3 py-2 rounded">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>

                        @if(request('email'))
                            <input type="hidden" name="email" value="{{ request('email') }}">

                            <div class="relative">
                                <input id="email_visual" type="email" disabled value="{{ request('email') }}"
                                       class="w-full border border-gray-300 rounded py-2 px-3 bg-gray-100 text-gray-600 cursor-not-allowed">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>
                        @else
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full border border-gray-300 rounded py-2 px-3 focus:border-orange-600 focus:ring-0">
                            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div x-data="{ show: false }">
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>

                            @if (Route::has('password.request'))
                                <a class="text-xs text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <input id="password" name="password" :type="show ? 'text' : 'password'"
                                   required autocomplete="current-password"
                                   class="w-full border border-gray-300 rounded py-2 px-3 focus:border-orange-600 focus:ring-0 text-gray-900 pr-10">
                            <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                                    tabindex="-1">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="remember_me" class="flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember"
                                   class="rounded border-gray-300 text-orange-600 focus:ring-orange-600">
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded">
                            Se connecter
                        </button>
                    </div>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>
