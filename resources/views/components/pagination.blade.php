@if ($paginator->hasPages())
    <nav class="flex justify-between">
        @if ($paginator->onFirstPage())
            <span class="text-menu-text px-4 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-lg opacity-50 cursor-not-allowed">
                &laquo; Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="text-menu-text px-4 py-2 border border-logo-gold bg-black bg-opacity-50 hover:bg-logo-gold hover:text-black rounded-lg transition-all duration-300">
                &laquo; Previous
            </a>
        @endif

        <div class="flex">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="text-menu-text px-4 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-lg">{{ $element }}</span>
                @endif

                @if (is_array($element))
                <div class="flex gap-4">
                @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="text-black font-bold px-4 py-2 border border-logo-gold bg-logo-gold rounded-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="text-menu-text px-4 py-2 border border-logo-gold bg-black bg-opacity-50 hover:bg-logo-gold hover:text-black rounded-lg transition-all duration-300">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                </div>
                    
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="text-menu-text px-4 py-2 border border-logo-gold bg-black bg-opacity-50 hover:bg-logo-gold hover:text-black rounded-lg transition-all duration-300">
                Next &raquo;
            </a>
        @else
            <span class="text-menu-text px-4 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-lg opacity-50 cursor-not-allowed">
                Next &raquo;
            </span>
        @endif
    </nav>
@endif
