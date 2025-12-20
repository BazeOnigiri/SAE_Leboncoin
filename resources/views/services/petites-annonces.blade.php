@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">Annonces à vérifier</h1>

        @if ($annonces->isEmpty())
            <p class="text-gray-500">Toutes les annonces ont été vérifiées.</p>
        @else
            <div class="space-y-4">
                @foreach ($annonces as $annonce)
                    <a href="{{ route('annonce.view', $annonce->idannonce) }}" class="mb-4 block">
                    <div class="relative bg-white shadow rounded-lg p-4 hover:shadow-md transition group">
                        <div class="flex items-center justify-between gap-4 relative z-10">
                            <div class="space-y-1">
                                <div class="text-xs text-gray-500">#{{ $annonce->idannonce }}</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $annonce->titreannonce }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $annonce->typeHebergement->nomtypehebergement ?? 'Type inconnu' }}
                                    •
                                    {{ $annonce->adresse->ville->nomville ?? 'Lieu inconnu' }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2 z-20">
                                <form action="{{ route('services-petites-annonces.verifier', $annonce->idannonce) }}" method="POST">
                                    @csrf
                                    <button type="submit" data-stop-prop class="px-3 py-2 rounded-md bg-[#EA580C] text-white text-sm font-semibold hover:bg-[#c2410c] border">Valider</button>
                                </form>
                                <form action="{{ route('services-petites-annonces.delete', $annonce->idannonce) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" data-stop-prop class="px-3 py-2 rounded-md bg-red-600 text-white text-sm font-semibold hover:bg-red-700 border">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('[data-stop-prop]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    </script>
    @endpush
@endsection