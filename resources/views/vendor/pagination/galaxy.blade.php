@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegação de Paginação" style="display: flex; justify-content: center; align-items: center; gap: 8px; margin: 32px 0;">
        {{-- Link para página anterior --}}
        @if ($paginator->onFirstPage())
            <span style="padding: 8px 16px; background: rgba(45, 27, 74, 0.3); border: 1px solid #B87CFF; border-radius: 8px; color: #6B3F9C; cursor: not-allowed; opacity: 0.5;">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding: 8px 16px; background: linear-gradient(135deg, #2D1B4A, #1B2B4A); border: 1px solid #B87CFF; border-radius: 8px; color: white; text-decoration: none; transition: all 0.3s; display: inline-block;" 
               onmouseover="this.style.background='linear-gradient(135deg, #6B3F9C, #4D7EFF)'; this.style.transform='scale(1.05)';" 
               onmouseout="this.style.background='linear-gradient(135deg, #2D1B4A, #1B2B4A)'; this.style.transform='scale(1)';">Anterior</a>
        @endif

        {{-- Links das páginas --}}
        <div style="display: flex; gap: 8px;">
            @foreach ($elements as $element)
                {{-- Separador de "..." --}}
                @if (is_string($element))
                    <span style="padding: 8px 16px; background: rgba(45, 27, 74, 0.3); border: 1px solid #B87CFF; border-radius: 8px; color: #6B3F9C;">{{ $element }}</span>
                @endif

                {{-- Links das páginas numéricas --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding: 8px 16px; background: linear-gradient(135deg, #B87CFF, #4D7EFF); border: 1px solid #B87CFF; border-radius: 8px; color: white; font-weight: bold;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="padding: 8px 16px; background: rgba(45, 27, 74, 0.3); border: 1px solid #B87CFF; border-radius: 8px; color: white; text-decoration: none; transition: all 0.3s; display: inline-block;"
                               onmouseover="this.style.background='rgba(75, 30, 120, 0.5)'; this.style.transform='scale(1.05)';"
                               onmouseout="this.style.background='rgba(45, 27, 74, 0.3)'; this.style.transform='scale(1)';">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Link para próxima página --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding: 8px 16px; background: linear-gradient(135deg, #2D1B4A, #1B2B4A); border: 1px solid #B87CFF; border-radius: 8px; color: white; text-decoration: none; transition: all 0.3s; display: inline-block;"
               onmouseover="this.style.background='linear-gradient(135deg, #6B3F9C, #4D7EFF)'; this.style.transform='scale(1.05)';"
               onmouseout="this.style.background='linear-gradient(135deg, #2D1B4A, #1B2B4A)'; this.style.transform='scale(1)';">Próxima</a>
        @else
            <span style="padding: 8px 16px; background: rgba(45, 27, 74, 0.3); border: 1px solid #B87CFF; border-radius: 8px; color: #6B3F9C; cursor: not-allowed; opacity: 0.5;">Próxima</span>
        @endif
    </nav>
@endif