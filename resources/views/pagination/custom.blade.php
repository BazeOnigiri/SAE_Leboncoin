@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-4">
        <div class="flex items-center space-x-2">
            @if ($paginator->onFirstPage())
                <span class="px-2 py-1 text-gray-300 cursor-default" aria-disabled="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="px-2 py-1 text-gray-500 hover:text-orange-600 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1 text-gray-500 cursor-default">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 text-sm font-bold text-white bg-orange-900 rounded-lg select-none" style="background-color: #0f3d64; /* Fallback/Original Blue replaced by Orange per user request */ background-color: rgb(234 88 12); /* Orange-600 */">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="px-3 py-1.5 text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors duration-200">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="px-2 py-1 text-gray-500 hover:text-orange-600 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            @else
                <span class="px-2 py-1 text-gray-300 cursor-default" aria-disabled="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
