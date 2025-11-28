<div>
    <button
        class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-[15px] shadow-sm hover:bg-gray-50 text-sm font-medium transition-colors">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="w-5 h-5 text-slate-800">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
        </svg>

        <span class="text-slate-900">
            <input 
                type="text" 
                class="border-0 outline-0 focus:outline-none focus:ring-0 bg-transparent"
                wire:model.live="search"
                wire:keydown.enter.prevent="selectFirstMatch" 
                placeholder="Rechercher une localisation..."
            />
        </span>

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
            stroke="currentColor" class="w-4 h-4 text-slate-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>

    </button>

    {{-- On affiche la liste seulement si on a tapé quelque chose ET qu'il y a des résultats --}}
    @if(strlen($search) > 0 && count($results) > 0)
    <div class="absolute z-10 bg-white shadow-lg rounded-md mt-2 w-72 max-h-60 overflow-y-auto">
        <ul>
            @foreach($results as $result)
                {{-- Logique pour déterminer le nom et le type à afficher --}}
                @php
                    $nom = $result['nomregion'] ?? ($result['nomdepartement'] ?? ($result['nomville'] ?? ''));
                    $type = isset($result['nomregion']) ? '(Région)' : (isset($result['nomdepartement']) ? '(Département)' : '');
                    
                    // Gestion du code postal ou numéro département s'ils existent
                    $info = '';
                    if (!empty($result['codepostal'])) {
                        $info = ' - ' . $result['codepostal'];
                    } elseif (!empty($result['numerodepartement'])) {
                        $info = ' - ' . $result['numerodepartement'];
                    }
                @endphp

                <li 
                    {{-- On utilise addslashes pour éviter les erreurs JS si le nom contient une apostrophe --}}
                    wire:click="selectOption('{{ addslashes($nom) }}')" 
                    class="p-2 hover:bg-gray-100 cursor-pointer text-left"
                >
                    {{ $nom }} <span class="text-gray-500 text-xs">{{ $type }}{{ $info }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>