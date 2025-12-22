@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">Utilisateurs à vérifier</h1>

        @if ($users->isEmpty())
            <p class="text-gray-500">Tous les utilisateurs ont été vérifiés.</p>
        @else
            <div class="space-y-4">
                @foreach ($users as $user)
                    <div class="relative bg-white shadow rounded-lg p-4 hover:shadow-md transition group">
                        <div class="flex items-center justify-between gap-4 relative z-10">
                            <div class="space-y-1">
                                <p class="text-xs text-gray-500">#{{ $user->idutilisateur }}</p>
                                @if($user->particulier())
                                    <p class="text-sm font-medium text-gray-900">Particulier</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->particulier->prenomutilisateur }} {{ $user->particulier->nomutilisateur }}</p>
                                    <p class="text-sm text-gray-600">Né(e) le {{ $user->particulier->dateNaissance ? \Carbon\Carbon::parse($user->particulier->dateNaissance->date)->format('d/m/Y') : 'Date inconnue' }}</p>
                                    @elseif($user->professionnel())
                                    <p class="text-sm font-medium text-gray-900">Professionnel</p>
                                @endif
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                <div class="flex space-x-4">
                                    <img src="data:image/jpeg;base64,{{base64_encode(\Illuminate\Support\Facades\Storage::disk('local')->get('/cni/' . $user->idutilisateur . '/recto/recto.jpg'))}}" alt="">
                                    <img src="data:image/jpeg;base64,{{base64_encode(\Illuminate\Support\Facades\Storage::disk('local')->get('/cni/' . $user->idutilisateur . '/verso/verso.jpg'))}}" alt="">
                                </div>
                                <div class=" flex space-x-4">
                                    <form action="" method="POST" data-stop-prop>
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Vérifier</button>
                                    </form>
                                    <form action="" method="POST" data-stop-prop>
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Rejeter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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