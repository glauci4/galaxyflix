@extends('layouts.app')

@section('title', 'Adicionar Filme')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 0 16px;">
    <!-- VOLTAR -->
    <div style="margin-bottom: 24px;">
        <a href="/filmes" class="link-galaxy" style="display: inline-flex; align-items: center;">
            ← Voltar
        </a>
    </div>

    <!-- CARD DO FORMULÁRIO (mais elegante) -->
    <div class="glow-container">
        <div class="card-galaxy" style="padding: 32px;">
            <h2 style="font-size: 28px; font-weight: bold; color: var(--galaxy-neon-purple); margin-bottom: 24px; text-align: center; text-shadow: 0 0 10px var(--galaxy-neon-purple);">
                Adicionar Filme
            </h2>
            
            <form method="POST" action="/filmes">
                @csrf
                
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- TÍTULO -->
                    <div>
                        <label for="titulo" style="display: block; font-size: 14px; color: var(--galaxy-light-purple); margin-bottom: 6px;">
                            Título
                        </label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" class="input-galaxy" placeholder="Ex: Interestelar" required>
                        @error('titulo')
                            <p style="margin-top: 4px; font-size: 14px; color: var(--galaxy-neon-purple);">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GÊNERO -->
                    <div>
                        <label for="genero" style="display: block; font-size: 14px; color: var(--galaxy-light-purple); margin-bottom: 6px;">
                            Gênero
                        </label>
                        <select name="genero" id="genero" class="input-galaxy" required>
                            <option value="" style="background: var(--galaxy-dark);">Selecione</option>
                            <option value="Ação" {{ old('genero') == 'Ação' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Ação</option>
                            <option value="Aventura" {{ old('genero') == 'Aventura' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Aventura</option>
                            <option value="Comédia" {{ old('genero') == 'Comédia' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Comédia</option>
                            <option value="Drama" {{ old('genero') == 'Drama' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Drama</option>
                            <option value="Ficção Científica" {{ old('genero') == 'Ficção Científica' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Ficção Científica</option>
                            <option value="Fantasia" {{ old('genero') == 'Fantasia' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Fantasia</option>
                            <option value="Terror" {{ old('genero') == 'Terror' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Terror</option>
                            <option value="Romance" {{ old('genero') == 'Romance' ? 'selected' : '' }} style="background: var(--galaxy-dark);">Romance</option>
                        </select>
                        @error('genero')
                            <p style="margin-top: 4px; font-size: 14px; color: var(--galaxy-neon-purple);">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ANO -->
                    <div>
                        <label for="ano" style="display: block; font-size: 14px; color: var(--galaxy-light-purple); margin-bottom: 6px;">
                            Ano
                        </label>
                        <input type="number" name="ano" id="ano" value="{{ old('ano') }}" class="input-galaxy" placeholder="Ex: 2024" min="1888" max="{{ date('Y') }}" required>
                        @error('ano')
                            <p style="margin-top: 4px; font-size: 14px; color: var(--galaxy-neon-purple);">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- BOTÃO -->
                    <div style="padding-top: 16px;">
                        <button type="submit" class="btn-galaxy" style="width: 100%; padding: 14px; font-size: 16px;">
                            Adicionar Filme
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection