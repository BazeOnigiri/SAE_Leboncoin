@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">Annonces à vérifier</h1>

        @if ($annonces->isEmpty())
            <p class="text-gray-500">Toutes les annonces ont été vérifiées.</p>
        @else
            <div class="space-y-4">
                @foreach ($annonces as $annonce)
                    <div class="relative bg-white shadow rounded-lg p-4 hover:shadow-md transition group">
                        <div class="flex items-center justify-between gap-4 relative z-10">
                            <div class="space-y-1 flex-1">
                                <a href="{{ route('annonce.view', ['id' => $annonce->idannonce]) }}">
                                    <p class="text-xs text-gray-500">#{{ $annonce->idannonce }}</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $annonce->titreannonce }}</p>
                                    <p class="text-sm text-gray-600">
                                        Publié par
                                        <a href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}"
                                            class="underline">{{ $annonce->utilisateur->email }}</a>
                                        le {{ $annonce->date->date->format('d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ Str::limit($annonce->descriptionannonce, 150) }}</p>
                                </a>
                                <div class=" flex space-x-4">
                                    {{-- <form action="{{ route('services.annonces.verify', ['id' => $annonce->idannonce]) }}" method="POST" data-stop-prop>
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Vérifier</button>
                                    </form>
                                    <form action="{{ route('services.annonces.reject', ['id' => $annonce->idannonce]) }}" method="POST" data-stop-prop>
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Rejeter</button>
                                    </form> --}}
                                </div>
                                <hr>
                                @if ($annonce->utilisateur->identity_verified)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-1"
                                        title="Identité vérifiée">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Identité
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 ml-1"
                                        title="Identité non vérifiée">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Identité
                                    </span>
                                @endif
                                    @if ($annonce->smsverifie)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-1"
                                            title="Téléphone vérifié">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                            Téléphone
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 ml-1"
                                            title="Téléphone non vérifié">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                            Téléphone
                                        </span>
                                    @endif
                                @if ($annonce->sms_verification_expires_at)
                                    <p class="text-sm text-gray-600">Code SMS : {{ $annonce->sms_verification_code }}</p>
                                    <p class="text-sm text-gray-600">Date d'expiration du code : {{ $annonce->sms_verification_expires_at->format('d/m/Y H:i') }}</p>
                                @else
                                    <p class="text-sm text-gray-600">Aucun code SMS généré.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
