@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Vérification par SMS
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Un code de vérification a été envoyé à votre téléphone pour valider votre annonce
            <span class="font-semibold">« {{ $annonce->titreannonce }} »</span>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            
            @if(session('dev_sms'))
                <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <p class="text-sm font-semibold text-purple-800 mb-1">🔧 Mode développement - SMS simulé :</p>
                    <p class="text-xs text-purple-700">À : {{ session('dev_sms')['to'] }}</p>
                    <p class="text-sm text-purple-900 font-mono mt-1">{{ session('dev_sms')['message'] }}</p>
                </div>
            @endif

            <form action="{{ route('annonce.verify-sms.submit', $annonce) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">
                        Code de vérification (6 chiffres)
                    </label>
                    <div class="mt-1">
                        <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]{6}" 
                               maxlength="6" required autofocus
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm text-center text-2xl tracking-widest font-mono"
                               placeholder="000000">
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Vérifier le code
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Vous n'avez pas reçu le code ?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <form action="{{ route('annonce.verify-sms.resend', $annonce) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Renvoyer un nouveau code
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('user.annonces') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Retour à mes annonces
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
