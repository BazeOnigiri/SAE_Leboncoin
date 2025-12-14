<x-guest-layout>
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ route('auth.check') }}" class="flex items-center gap-2 text-gray-600 hover:text-orange-600 font-medium transition">
            <i class="fa-solid fa-arrow-left"></i> 
            <span>Retour</span>
        </a>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 py-12">
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
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block font-bold text-sm text-gray-700 mb-1">E-mail</label>
                    
                    @if(request('email'))        
                        <input type="hidden" name="email" value="{{ request('email') }}">
                        <div class="relative">
                            <input id="email_visual" type="email" disabled value="{{ request('email') }}" class="w-full border-gray-300 rounded-[10px] py-3 px-4 bg-gray-100 text-gray-500 cursor-not-allowed">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    @else
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div x-data="{ show: false }">
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block font-bold text-sm text-gray-700">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs text-gray-500 hover:text-[#ec5a13] transition" href="{{ route('password.request') }}">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>
                    
                    <div class="relative">
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="current-password" class="w-full border-gray-300 rounded-[10px] py-3 px-4 focus:border-[#ec5a13] focus:ring-0 text-gray-900 pr-10">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer"tabindex="-1">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="block">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-[#ec5a13] shadow-sm focus:ring-[#ec5a13]">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full bg-[#ec5a13] hover:bg-[#d64d0e] text-white font-bold py-3 px-4 rounded-[18px] transition duration-200 text-center shadow-md hover:shadow-lg">
                        Se connecter
                    </button>
                </div>
            </form>
        </x-authentication-card>
    </div>
</x-guest-layout>