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
            <input type="text" wire:model.live="query" class="border-0 outline-0 focus:outline-none focus:ring-0 bg-transparent"
                placeholder="Choisir une destination" />
        </span>

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
            stroke="currentColor" class="w-4 h-4 text-slate-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>

    </button>

    @if (strlen($query) > 0)
        <ul class=" absolute bg-white rounded-md shadow-lg mt-2 w-72 max-h-60 overflow-y-auto z-30">
            @foreach ($results as $result)
                <li class="p-2 hover:bg-gray-200 cursor-pointer">
                    {{ $result['nomregion'] ?? '' }}
                    {{ $result['nomdepartement'] ?? '' }}
                    {{ $result['nomville'] ?? '' }}

                    @if(!empty($result['codepostal']) || !empty($result['numerodepartement']))
                        ({{ $result['codepostal'] ?? '' }}{{ !empty($result['codepostal']) && !empty($result['numerodepartement']) ? ' - ' : '' }}{{ $result['numerodepartement'] ?? '' }})
                    @endif
                </li>
            @endforeach
        </ul>
    @else
    @endif

</div>
