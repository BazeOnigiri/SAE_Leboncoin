@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">Annonces à vérifier (Service Immobilier)</h1>
        <p class="text-gray-600 mb-4">
            Liste des annonces en attente de validation finale. Les propriétaires ont leur identité confirmée.
        </p>

        @if ($annonces->isEmpty())
            <p class="text-gray-500">Aucune annonce à vérifier pour le moment.</p>
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
                                        le {{ $annonce->datePublication?->date?->format('d/m/Y') ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ Str::limit($annonce->descriptionannonce, 150) }}</p>
                                </a>

                                <hr class="my-2">

                                <div class="flex flex-wrap gap-2 items-center mb-3">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"
                                        title="Identité vérifiée">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Identité vérifiée
                                    </span>

                                    @if ($annonce->smsverifie)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"
                                            title="Téléphone vérifié">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                            Téléphone
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"
                                            title="Téléphone non vérifié">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                            Téléphone
                                        </span>
                                    @endif

                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800"
                                        title="En attente de validation">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        En attente
                                    </span>
                                </div>

                                <div class="flex flex-wrap gap-2 items-center">
                                    <form action="{{ route('services.immobilier.accepter', ['id' => $annonce->idannonce]) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded text-sm font-medium bg-green-600 hover:bg-green-700 text-white transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Valider l'annonce
                                        </button>
                                    </form>

                                    <button type="button" 
                                        onclick="openRejectModal({{ $annonce->idannonce }}, '{{ addslashes($annonce->titreannonce) }}')"
                                        class="px-4 py-2 rounded text-sm font-medium bg-red-600 hover:bg-red-700 text-white transition flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rejeter l'annonce
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Rejeter l'annonce</h3>
            <p class="text-sm text-gray-600 mb-4">
                Vous êtes sur le point de rejeter et supprimer l'annonce <strong id="rejectModalTitle"></strong>.
                Cette action est irréversible.
            </p>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="motif" class="block text-sm font-medium text-gray-700 mb-1">Motif du rejet (optionnel)</label>
                    <textarea name="motif" id="motif" rows="3" 
                        class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Expliquez pourquoi l'annonce est rejetée..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition">
                        Annuler
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded transition">
                        Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(annonceId, titre) {
            document.getElementById('rejectModalTitle').textContent = '« ' + titre + ' »';
            document.getElementById('rejectForm').action = '{{ url("services/immobilier/annonces") }}/' + annonceId + '/rejeter';
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
            document.getElementById('motif').value = '';
        }

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection
