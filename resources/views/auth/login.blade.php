<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ route('auth.check') }}" class="flex items-center gap-2 text-gray-600 hover:text-orange-600 font-medium transition">
            <i class="fa-solid fa-arrow-left"></i> 
            <span>Retour</span>
        </a>
    </div>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        <h1 class="text-xl font-bold text-center text-gray-900 mb-2">
            Bon retour parmi nous !
        </h1>
        <p class="text-center text-gray-500 text-sm mb-8">
            Connectez-vous pour accéder à votre espace
        </p>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />            
                @if(request('email'))        
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    <div class="relative mt-1">
                        <x-input id="email_visual" class="block w-full bg-gray-100 text-gray-600 cursor-not-allowed border-gray-300" type="email" disabled value="{{ request('email') }}" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                    </div>
                @else
                    <x-input id="email" class="block mt-1 w-full bg-white text-gray-900" type="email" name="email" :value="old('email')" required autofocus />
                @endif
            </div>

            <div class="mt-4" x-data="{ show: false }">
                <x-label for="password" value="{{ __('Mot de passe') }}" />
                <div class="relative mt-1">
                    <x-input id="password" class="block w-full pr-10" type="password" name="password" required autocomplete="current-password" x-bind:type="show ? 'text' : 'password'"/>
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer"tabindex="-1">
                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                </label>
            </div> -->

            <div class="flex items-center justify-end mt-4">
                <!-- @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif -->

                <button type="submit" class="w-full bg-[#ec5a13] hover:bg-[#d64d0e] text-white font-bold py-3 px-4 rounded-[18px] transition duration-200 text-center mt-6 shadow-md hover:shadow-lg">
                    Se connecter
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>