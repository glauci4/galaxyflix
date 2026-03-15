@extends('layouts.app')

@section('title', 'Editar Filme')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 0 16px;">
    <!-- BOTÃO VOLTAR -->
    <div style="margin-bottom: 24px;">
        <a href="/filmes" class="link-galaxy" style="display: inline-flex; align-items: center;">
            ← Voltar
        </a>
    </div>

    <!-- CARD DO FORMULÁRIO -->
    <div class="glow-container">
        <div class="card-galaxy" style="padding: 32px;">
            <h2 style="font-size: 28px; font-weight: bold; color: var(--galaxy-neon-purple); margin-bottom: 24px; text-align: center; text-shadow: 0 0 10px var(--galaxy-neon-purple);">
                Editar Filme
            </h2>
            
            <form method="POST" action="/filmes/{{ $filme->id }}">
                @csrf
                @method('PUT')
                
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- CAMPO TÍTULO -->
                    <div>
                        <label for="titulo" style="display: block; font-size: 14px; color: var(--galaxy-light-purple); margin-bottom: 6px;">
                            Título
                        </label>
                        <input type="text" 
                               name="titulo" 
                               id="titulo" 
                               value="{{ old('titulo', $filme->titulo) }}" 
                               class="input-galaxy @error('titulo') input-erro @enderror" 
                               placeholder="Ex: Velozes e Furiosos 5">
                        
                        <!-- MENSAGEM DE INSTRUÇÃO -->
                        <div style="font-size: 13px; color: var(--galaxy-light-purple); margin-top: 4px; padding-left: 4px; border-left: 2px solid var(--galaxy-neon-purple);">
                            Digite o título exato do filme (opcional)
                        </div>
                        
                        <!-- MENSAGEM DE ERRO -->
                        @error('titulo')
                            <div style="margin-top: 6px; padding: 8px 12px; background: rgba(255, 68, 68, 0.15); border: 1px solid #ff4444; border-radius: 6px; color: #ff8888; font-size: 14px;">
                                 {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- CAMPO GÊNERO -->
                    <div>
                        <label for="genero" style="display: block; font-size: 14px; color: var(--galaxy-light-purple); margin-bottom: 6px;">
                            Gênero
                        </label>
                        <input type="text" 
                               name="genero" 
                               id="genero" 
                               value="{{ old('genero', $filme->genero) }}" 
                               class="input-galaxy @error('genero') input-erro @enderror" 
                               placeholder="Ex: Ação, Fantasia, Comédia..."
                               required>
                        
                        <!-- MENSAGEM DE INSTRUÇÃO -->
                        <div style="font-size: 13px; color: var(--galaxy-light-purple); margin-top: 4px; padding-left: 4px; border-left: 2px solid var(--galaxy-neon-purple);">
                            Gêneros comuns: Ação, Aventura, Comédia, Drama, Fantasia, Ficção Científica, Terror, Romance
                        </div>
                        
                        <!-- MENSAGEM DE ERRO -->
                        @error('genero')
                            <div style="margin-top: 6px; padding: 8px 12px; background: rgba(255, 68, 68, 0.15); border: 1px solid #ff4444; border-radius: 6px; color: #ff8888; font-size: 14px;">
                                 {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- CAMPO ANO -->
                    <div>
                        <label for="ano" style="display: block; font-size: 14px; color: var(--galaxy-light-purple); margin-bottom: 6px;">
                            Ano
                        </label>
                        <input type="number" 
                               name="ano" 
                               id="ano" 
                               value="{{ old('ano', $filme->ano) }}" 
                               class="input-galaxy @error('ano') input-erro @enderror" 
                               placeholder="Ex: 2011" 
                               min="1888" 
                               max="{{ date('Y') }}" 
                               required>
                        
                        <!-- MENSAGEM DE INSTRUÇÃO -->
                        <div style="font-size: 13px; color: var(--galaxy-light-purple); margin-top: 4px; padding-left: 4px; border-left: 2px solid var(--galaxy-neon-purple);">
                            Digite o ano exato de lançamento (ex: 2011, 2023)
                        </div>
                        
                        <!-- MENSAGEM DE ERRO -->
                        @error('ano')
                            <div style="margin-top: 6px; padding: 8px 12px; background: rgba(255, 68, 68, 0.15); border: 1px solid #ff4444; border-radius: 6px; color: #ff8888; font-size: 14px;">
                                 {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- BOTÕES -->
                    <div style="padding-top: 16px; display: flex; gap: 16px;">
                        <button type="submit" class="btn-galaxy" style="flex: 1; padding: 12px;">
                            Atualizar
                        </button>
                        
                        <a href="/filmes" class="btn-galaxy-neon" style="flex: 1; padding: 12px; text-align: center; text-decoration: none;">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
// Estilo para campo com erro 
.input-erro {
    border-color: #ff4444 !important;
    box-shadow: 0 0 8px rgba(255, 68, 68, 0.5) !important;
}
</style>
@endsection