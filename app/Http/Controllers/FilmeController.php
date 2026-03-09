<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filme;
use Illuminate\Support\Facades\Http;

class FilmeController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        if ($busca) {
            $filmes = Filme::where('titulo', 'like', '%' . $busca . '%')
                ->orWhere('genero', 'like', '%' . $busca . '%')
                ->orWhere('ano', 'like', '%' . $busca . '%')
                ->paginate(5);
        } else {
            $filmes = Filme::paginate(5);
        }

        return view('filmes.index', compact('filmes'));
    }

    public function create()
    {
        return view('filmes.create');
    }

    public function store(Request $request)
    {
        $anoAtual = date('Y');

        $request->validate([
            'titulo' => 'required|string|max:255|unique:filmes,titulo',
            'genero' => 'required|string|max:100',
            'ano' => "required|integer|min:1888|max:$anoAtual"
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.unique' => 'Este filme já foi cadastrado.',
            'genero.required' => 'O gênero é obrigatório.',
            'ano.required' => 'O ano é obrigatório.',
            'ano.max' => 'O ano não pode ser maior que o atual.',
            'ano.min' => 'Ano inválido para um filme.'
        ]);

        $dadosApi = $this->buscarFilmeApi($request->titulo);

        $filmeApi = $dadosApi['results'][0] ?? [];

        Filme::create([
            'titulo' => $request->titulo,
            'genero' => $request->genero,
            'ano' => $request->ano,
            'poster' => isset($filmeApi['poster_path'])
                ? 'https://image.tmdb.org/t/p/w500' . $filmeApi['poster_path']
                : null,
            'sinopse' => $filmeApi['overview'] ?? null,
            'nota' => $filmeApi['vote_average'] ?? null
        ]);

        return redirect('/filmes')->with('sucesso', 'Filme cadastrado com sucesso!');
    }

    public function show(Filme $filme)
    {
        return view('filmes.edit', compact('filme'));
    }

    public function edit(Filme $filme)
    {
        return view('filmes.edit', compact('filme'));
    }

    public function update(Request $request, Filme $filme)
    {
        $anoAtual = date('Y');

        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'genero' => 'required|string|max:100',
            'ano' => "required|integer|min:1888|max:$anoAtual"
        ], [
            'genero.required' => 'O gênero é obrigatório.',
            'ano.required' => 'O ano é obrigatório.',
            'ano.max' => 'O ano não pode ser maior que o ano atual.',
        ]);

        $filme->update($request->all());

        return redirect('/filmes')->with('sucesso', '✔ Filme atualizado com sucesso!');
    }

    public function destroy(Filme $filme)
    {
        $filme->delete();

        return redirect()->route('filmes.index');
    }

public function buscarFilmeApi($titulo)
{
    $response = Http::withOptions([
        'verify' => base_path('cacert.pem'),
    ])->get('https://api.themoviedb.org/3/search/movie', [
        'api_key' => env('TMDB_API_KEY'),
        'query' => $titulo,
        'language' => 'pt-BR'
    ]);

    return $response->json();
} 

}