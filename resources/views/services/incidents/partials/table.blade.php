<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
            <th class="px-6 py-4">Date</th>
            <th class="px-6 py-4">Utilisateur</th>
            <th class="px-6 py-4">Étape / État</th>
            <th class="px-6 py-4 text-right">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        @forelse($incidents as $incident)
            <tr class="hover:bg-gray-50 transition-colors {{ $incident->estclasse ? 'opacity-70 bg-gray-50' : '' }}">
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $incident->dateRecord ? \Carbon\Carbon::parse($incident->dateRecord->date)->format('d/m/Y') : 'N/C' }}
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                    {{ $incident->user->pseudonyme ?? 'Anonyme' }}
                </td>
                <td class="px-6 py-4">
                    @if($incident->estrembourse)
                        <span class="px-2 py-1 bg-gray-200 text-gray-600 rounded text-xs font-bold uppercase">
                            Remboursé
                        </span>
                    @elseif($incident->estclasse)
                        <span class="px-2 py-1 bg-gray-200 text-gray-600 rounded text-xs font-bold uppercase">
                            Classé sans suite
                        </span>
                    @elseif($incident->etape == 1)
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">
                            [1] Analyse du signalement
                        </span>
                    @elseif($incident->etape == 2)
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-bold uppercase">
                            [2] En attente de réponse du propriétaire
                        </span>
                    @elseif($incident->etape == 3)
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">
                            [3] Analyse des explications
                        </span>
                    @elseif($incident->etape == 4)
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">
                            [4] En attente de la décision du locataire
                        </span>
                    @elseif($incident->etape == 5)
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                            [5] Décision contestée par le locataire
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    {{-- Bouton pour ouvrir la modale avec toutes les infos --}}
                    <button type="button" 
                        onclick="openIncidentModal({{ json_encode([
                            'id' => $incident->idincident,
                            'user' => $incident->user->pseudonyme ?? 'Anonyme',
                            'motif' => $incident->motifincident,
                            'description' => $incident->descriptionincident,
                            'date' => $incident->dateRecord ? \Carbon\Carbon::parse($incident->dateRecord->date)->format('d/m/Y') : 'N/C',
                            'etape' => $incident->etape,
                            'estclasse' => $incident->estclasse
                        ]) }})"
                        class="text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-full hover:bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">
                    Aucun incident dans cette catégorie.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>