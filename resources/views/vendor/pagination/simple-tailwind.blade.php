@if ($paginator->hasPages())
    <nav class="flex items-center justify-between" aria-label="Pagination">
        <p class="text-xs text-gray-600">
            Page {{ $paginator->currentPage() }}
        </p>
        <div class="flex gap-2">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-gray-700 glass rounded-lg cursor-not-allowed">← Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1.5 text-xs text-gray-400 hover:text-white glass rounded-lg transition-colors">← Prev</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1.5 text-xs text-gray-400 hover:text-white glass rounded-lg transition-colors">Next →</a>
            @else
                <span class="px-3 py-1.5 text-xs text-gray-700 glass rounded-lg cursor-not-allowed">Next →</span>
            @endif
        </div>
    </nav>
@endif