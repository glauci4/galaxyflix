@extends('layouts.app')

@section('title', 'GalaxyFlix')

@section('content')
<div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
    <!-- TÍTULO -->
    <h1 class="titulo-neon" style="text-align: center; margin-bottom: 8px;">GalaxyFlix</h1>
    
    <!-- BOTÃO CADASTRAR FILME (discreto e elegante) -->
    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <a href="/filmes/create" class="btn-galaxy-neon" style="padding: 6px 20px; font-size: 14px;">
            + Cadastrar filme
        </a>
    </div>
    
    <!-- FORMULÁRIO DE BUSCA -->
    <div style="display: flex; justify-content: center; margin-bottom: 32px;">
        <form method="GET" action="/filmes" style="display: flex; width: 100%; max-width: 400px;">
            <input type="text" name="busca" placeholder="Buscar filmes..." value="{{ request('busca') }}" class="input-galaxy">
            <button type="submit" class="btn-galaxy" style="margin-left: 8px;">Buscar</button>
            @if(request('busca'))
                <a href="/filmes" style="margin-left: 8px;">
                    <button type="button" class="btn-galaxy-neon">Limpar</button>
                </a>
            @endif
        </form>
    </div>

    <!-- MENSAGEM DE SUCESSO -->
    @if(session('sucesso'))
    <div style="background: rgba(184,124,255,0.2); border: 1px solid var(--galaxy-neon-purple); color: var(--galaxy-neon-purple); padding: 16px 24px; border-radius: 8px; margin-bottom: 24px; animation: pulse 2s infinite; backdrop-filter: blur(8px); text-align: center;" id="mensagem-sucesso">
        {{ session('sucesso') }}
    </div>
    @endif

    <!-- GRID DE FILMES -->
    <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 24px;">
        <style>
            @media (min-width: 640px) { .grid-container { grid-template-columns: repeat(2, 1fr); } }
            @media (min-width: 1024px) { .grid-container { grid-template-columns: repeat(3, 1fr); } }
            @media (min-width: 1280px) { .grid-container { grid-template-columns: repeat(4, 1fr); } }
        </style>
        <div class="grid-container" style="display: grid; gap: 24px;">
            @forelse($filmes as $filme)
            <div class="card-galaxy" style="transition: all 0.3s;">
                <!-- CAPA -->
                <div style="height: 256px; overflow: hidden; border-radius: 12px 12px 0 0; position: relative;">
                    @if($filme->poster && $filme->poster != 'N/A')
                        <img src="{{ $filme->poster }}" alt="{{ $filme->titulo }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--galaxy-purple), var(--galaxy-blue)); display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 48px; animation: float 6s infinite;">🎬</span>
                        </div>
                    @endif
                    
                    <!-- NOTA -->
                    @if($filme->nota)
                    <div class="nota-badge">
                        ⭐ {{ number_format($filme->nota, 1) }}
                    </div>
                    @endif
                </div>
                
                <!-- INFORMAÇÕES -->
                <div style="padding: 16px;">
                    <h3 style="font-size: 20px; font-weight: bold; color: white; margin-bottom: 4px;">{{ $filme->titulo }}</h3>
                    
                    <div style="display: flex; gap: 8px; font-size: 14px; color: var(--galaxy-light-purple); margin-bottom: 8px;">
                        <span>{{ $filme->ano }}</span>
                        <span>•</span>
                        <span>{{ $filme->genero }}</span>
                    </div>
                    
                    @if($filme->sinopse)
                    <p style="color: #ccc; font-size: 14px; margin-bottom: 16px; max-height: 60px; overflow: hidden;">
                        {{ Str::limit($filme->sinopse, 100) }}
                    </p>
                    @endif
                    
                    <!-- AÇÕES -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--galaxy-neon-purple);">
                        <a href="/filmes/{{ $filme->id }}/edit" class="link-galaxy">Editar</a>
                        
                        <form action="/filmes/{{ $filme->id }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: var(--galaxy-neon-purple); background: none; border: none; cursor: pointer; transition: color 0.2s;" 
                                    onmouseover="this.style.color='var(--galaxy-neon-blue)'" 
                                    onmouseout="this.style.color='var(--galaxy-neon-purple)'">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <!-- ESTADO VAZIO -->
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 24px;" class="card-galaxy">
                <p style="color: var(--galaxy-light-purple); font-size: 24px; margin-bottom: 24px;">Nenhum filme encontrado</p>
                <a href="/filmes/create" class="btn-galaxy" style="padding: 12px 32px; font-size: 16px;">
                    Cadastrar um filme
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- PAGINAÇÃO -->
    <div style="margin-top: 32px;">
        {{ $filmes->links() }}
    </div>
</div>

<script>
    setTimeout(function() {
        var mensagem = document.getElementById('mensagem-sucesso');
        if (mensagem) {
            mensagem.style.opacity = '0';
            setTimeout(() => mensagem.style.display = 'none', 500);
        }
    }, 3000);
</script>
@endsection