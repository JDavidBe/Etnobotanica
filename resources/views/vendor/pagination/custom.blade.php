@if ($paginator->hasPages())
    <nav class="pagina-nav" role="navigation" aria-label="Navegación de paginación">
        @if ($paginator->onFirstPage())
            <span class="pagina-btn disabled" aria-disabled="true">&lsaquo; Anterior</span>
        @else
            <a class="pagina-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior">&lsaquo; Anterior</a>
        @endif

        <div class="pagina-nums">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagina-dots" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagina-num activa" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagina-num" href="{{ $url }}" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a class="pagina-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente">Siguiente &rsaquo;</a>
        @else
            <span class="pagina-btn disabled" aria-disabled="true">Siguiente &rsaquo;</span>
        @endif
    </nav>

    <p class="pagina-info">
        @if ($paginator->firstItem())
            Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
        @else
            Mostrando {{ $paginator->count() }} resultados
        @endif
    </p>
@endif