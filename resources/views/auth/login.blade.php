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
                    </div>$
                </div>
                @else
                <x-input id="email" class="block mt-1 w-full bg-white text-gray-900" type="email" name="email" :value="old('email')" required autofocus />
                @endif
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Mot de passe') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif

                <x-button class="ms-4 bg-orange-600 hover:bg-orange-700">
                    {{ __('Se connecter') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>