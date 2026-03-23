<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filme;
use Illuminate\Support\Facades\Http;

class FilmeController extends Controller
{
    /**
     * MAPEAMENTO DE GÊNEROS DO TMDB
     * A API retorna IDs numéricos, este array converte para nomes em português
     * Exemplo: ID 28 vira "Ação", ID 14 vira "Fantasia"
     */
    private $generosMap = [
        28 => 'Ação',
        12 => 'Aventura',
        16 => 'Animação',
        35 => 'Comédia',
        80 => 'Crime',
        99 => 'Documentário',
        18 => 'Drama',
        10751 => 'Família',
        14 => 'Fantasia',
        36 => 'História',
        27 => 'Terror',
        10402 => 'Música',
        9648 => 'Mistério',
        10749 => 'Romance',
        878 => 'Ficção Científica',
        53 => 'Thriller',
        10752 => 'Guerra',
        37 => 'Faroeste'
    ];

    /**
     * EXIBE A LISTA DE FILMES
     * Mostra todos os filmes com paginação de 4 itens por página
     * Se houver busca, filtra os resultados por título, gênero ou ano
     */
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        if ($busca) {
            $filmes = Filme::where('titulo', 'like', '%' . $busca . '%')
                ->orWhere('genero', 'like', '%' . $busca . '%')
                ->orWhere('ano', 'like', '%' . $busca . '%')
                ->paginate(4);
        } else {
            $filmes = Filme::paginate(4);
        }

        return view('filmes.index', compact('filmes'));
    }

    /**
     * EXIBE O FORMULÁRIO DE CADASTRO
     */
    public function create()
    {
        return view('filmes.create');
    }

    /**
     * SALVA UM NOVO FILME COM VALIDAÇÃO COMPLETA DA API
     * Valida: título, ano e gênero com os dados do TMDB
     * Acumula todos os erros para mostrar de uma vez
     */
    public function store(Request $request)
    {
        $anoAtual = date('Y');
        $erros = []; // Array para acumular todos os erros

        // VALIDAÇÃO DOS CAMPOS DO FORMULÁRIO
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

        // BUSCA NA API TMDB
        $resultados = $this->buscarFilmeApi($request->titulo);
        
        if (empty($resultados)) {
            return redirect()->back()
                ->withErrors(['titulo' => 'Nenhum filme encontrado com este título no TMDB.'])
                ->withInput();
        }
        
        // VALIDAÇÃO DO TÍTULO
        $tituloDigitado = trim($request->titulo);
        $tituloEncontrado = false;
        $possiveisTitulos = [];
        $possiveisFilmes = []; // Guarda todos os filmes para usar depois
        
        foreach ($resultados as $filme) {
            $possiveisFilmes[] = $filme; // Guarda todos os resultados
            // Compara títulos ignorando acentos e maiúsculas
            if ($this->compararTitulos($tituloDigitado, $filme['title']) || 
                $this->compararTitulos($tituloDigitado, $filme['original_title'])) {
                $tituloEncontrado = true;
            }
            $possiveisTitulos[] = $filme['title'];
        }
        
        if (!$tituloEncontrado) {
            $sugestoes = implode(', ', array_slice($possiveisTitulos, 0, 3));
            $erros['titulo'] = "Título não encontrado. Títulos próximos: $sugestoes";
        }
        
        // VALIDAÇÃO DO ANO
        $anoDigitado = $request->ano;
        $filmesDoAnoCorreto = [];
        $anosDisponiveis = [];
        
        // Primeiro: coleta todos os anos disponíveis
        foreach ($possiveisFilmes as $filme) {
            $anoApi = date('Y', strtotime($filme['release_date']));
            if (!in_array($anoApi, $anosDisponiveis)) {
                $anosDisponiveis[] = $anoApi;
            }
            if ($anoApi == $anoDigitado) {
                $filmesDoAnoCorreto[] = $filme;
            }
        }
        sort($anosDisponiveis);
        
        // Se não encontrou nenhum filme com o ano digitado
        if (empty($filmesDoAnoCorreto)) {
            $anosStr = implode(', ', $anosDisponiveis);
            $erros['ano'] = "Ano incorreto. Anos disponíveis para este título: $anosStr";
        }
        
        // VALIDAÇÃO DO GÊNERO
        $generoDigitado = trim($request->genero);
        $generoEncontrado = false;
        $possiveisGeneros = [];
        $filmeCorrespondente = null;
        
        // Usa os filmes do ano correto se existirem, senão usa todos
        $filmesParaValidarGenero = !empty($filmesDoAnoCorreto) ? $filmesDoAnoCorreto : $possiveisFilmes;
        
        foreach ($filmesParaValidarGenero as $filme) {
            if (isset($filme['genre_ids']) && is_array($filme['genre_ids'])) {
                foreach ($filme['genre_ids'] as $genreId) {
                    $generoApi = $this->generosMap[$genreId] ?? '';
                    if (!in_array($generoApi, $possiveisGeneros) && !empty($generoApi)) {
                        $possiveisGeneros[] = $generoApi;
                    }
                    if ($this->compararGeneros($generoDigitado, $generoApi)) {
                        $generoEncontrado = true;
                        $filmeCorrespondente = $filme; // Guarda o filme encontrado
                    }
                }
            }
        }
        
        // Remove duplicatas e gêneros vazios
        $possiveisGeneros = array_unique(array_filter($possiveisGeneros));
        sort($possiveisGeneros);
        
        if (!$generoEncontrado && !empty($possiveisGeneros)) {
            $generosStr = implode(', ', $possiveisGeneros);
            $erros['genero'] = "Gênero incorreto. Gêneros disponíveis: $generosStr";
        }
        
        // SE HOUVER ERROS, MOSTRA TUDO DE UMA VEZ
        if (!empty($erros)) {
            return redirect()->back()
                ->withErrors($erros)
                ->withInput();
        }
        
        // CRIA O FILME NO BANCO DE DADOS
        Filme::create([
            'titulo' => $request->titulo,
            'genero' => $request->genero,
            'ano' => $request->ano,
            'poster' => isset($filmeCorrespondente['poster_path'])
                ? 'https://image.tmdb.org/t/p/w500' . $filmeCorrespondente['poster_path']
                : null,
            'sinopse' => $filmeCorrespondente['overview'] ?? null,
            'nota' => $filmeCorrespondente['vote_average'] ?? null
        ]);

        return redirect('/filmes')->with('sucesso', 'Filme cadastrado com sucesso!');
    }

    /**
     * EXIBE O FORMULÁRIO DE EDIÇÃO
     */
    public function edit(Filme $filme)
    {
        return view('filmes.edit', compact('filme'));
    }

    /**
     * ATUALIZA UM FILME EXISTENTE COM VALIDAÇÃO COMPLETA DA API
     * Valida: título, ano e gênero com os dados do TMDB
     * Acumula todos os erros para mostrar de uma vez
     */
    public function update(Request $request, Filme $filme)
    {
        $anoAtual = date('Y');
        $erros = []; // Array para acumular todos os erros

        // VALIDAÇÃO DOS CAMPOS DO FORMULÁRIO
        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'genero' => 'required|string|max:100',
            'ano' => "required|integer|min:1888|max:$anoAtual"
        ], [
            'genero.required' => 'O gênero é obrigatório.',
            'ano.required' => 'O ano é obrigatório.',
            'ano.max' => 'O ano não pode ser maior que o ano atual.',
        ]);

        // BUSCA NA API TMDB COM O TÍTULO ATUALIZADO
        $resultados = $this->buscarFilmeApi($request->titulo);
        
        if (empty($resultados)) {
            return redirect()->back()
                ->withErrors(['titulo' => 'Nenhum filme encontrado com este título no TMDB.'])
                ->withInput();
        }
        
        // VALIDAÇÃO DO TÍTULO
        $tituloDigitado = trim($request->titulo);
        $tituloEncontrado = false;
        $possiveisTitulos = [];
        $possiveisFilmes = [];
        
        foreach ($resultados as $filmeApi) {
            $possiveisFilmes[] = $filmeApi;
            if ($this->compararTitulos($tituloDigitado, $filmeApi['title']) || 
                $this->compararTitulos($tituloDigitado, $filmeApi['original_title'])) {
                $tituloEncontrado = true;
            }
            $possiveisTitulos[] = $filmeApi['title'];
        }
        
        if (!$tituloEncontrado) {
            $sugestoes = implode(', ', array_slice($possiveisTitulos, 0, 3));
            $erros['titulo'] = "Título não encontrado. Títulos próximos: $sugestoes";
        }
        
        // VALIDAÇÃO DO ANO
        $anoDigitado = $request->ano;
        $filmesDoAnoCorreto = [];
        $anosDisponiveis = [];
        
        foreach ($possiveisFilmes as $filmeApi) {
            $anoApi = date('Y', strtotime($filmeApi['release_date']));
            if (!in_array($anoApi, $anosDisponiveis)) {
                $anosDisponiveis[] = $anoApi;
            }
            if ($anoApi == $anoDigitado) {
                $filmesDoAnoCorreto[] = $filmeApi;
            }
        }
        sort($anosDisponiveis);
        
        if (empty($filmesDoAnoCorreto)) {
            $anosStr = implode(', ', $anosDisponiveis);
            $erros['ano'] = "Ano incorreto. Anos disponíveis para este título: $anosStr";
        }
        
        // VALIDAÇÃO DO GÊNERO
        $generoDigitado = trim($request->genero);
        $generoEncontrado = false;
        $possiveisGeneros = [];
        $filmeApiCorrespondente = null;
        
        $filmesParaValidarGenero = !empty($filmesDoAnoCorreto) ? $filmesDoAnoCorreto : $possiveisFilmes;
        
        foreach ($filmesParaValidarGenero as $filmeApi) {
            if (isset($filmeApi['genre_ids']) && is_array($filmeApi['genre_ids'])) {
                foreach ($filmeApi['genre_ids'] as $genreId) {
                    $generoApi = $this->generosMap[$genreId] ?? '';
                    if (!in_array($generoApi, $possiveisGeneros) && !empty($generoApi)) {
                        $possiveisGeneros[] = $generoApi;
                    }
                    if ($this->compararGeneros($generoDigitado, $generoApi)) {
                        $generoEncontrado = true;
                        $filmeApiCorrespondente = $filmeApi;
                    }
                }
            }
        }
        
        $possiveisGeneros = array_unique(array_filter($possiveisGeneros));
        sort($possiveisGeneros);
        
        if (!$generoEncontrado && !empty($possiveisGeneros)) {
            $generosStr = implode(', ', $possiveisGeneros);
            $erros['genero'] = "Gênero incorreto. Gêneros disponíveis: $generosStr";
        }
        
        // SE HOUVER ERROS, MOSTRA TUDO DE UMA VEZ
        if (!empty($erros)) {
            return redirect()->back()
                ->withErrors($erros)
                ->withInput();
        }
        
        // PREPARA OS DADOS ATUALIZADOS COM OS NOVOS DADOS DA API
        $dadosAtualizados = [
            'titulo' => $request->titulo,
            'genero' => $request->genero,
            'ano' => $request->ano,
            'poster' => isset($filmeApiCorrespondente['poster_path'])
                ? 'https://image.tmdb.org/t/p/w500' . $filmeApiCorrespondente['poster_path']
                : null,
            'sinopse' => $filmeApiCorrespondente['overview'] ?? null,
            'nota' => $filmeApiCorrespondente['vote_average'] ?? null
        ];
        
        // ATUALIZA O FILME NO BANCO DE DADOS
        $filme->update($dadosAtualizados);

        return redirect('/filmes')->with('sucesso', 'Filme atualizado com sucesso!');
    }

    /**
     * EXCLUI UM FILME
     */
    public function destroy(Filme $filme)
    {
        $filme->delete();
        return redirect()->route('filmes.index');
    }

    /**
     * BUSCA FILMES NA API DO TMDB
     * @param string $titulo - Título do filme a ser buscado
     * @param int|null $ano - Ano para filtrar (opcional)
     * @return array - Lista de filmes encontrados
     */
    public function buscarFilmeApi($titulo, $ano = null)
    {
        // Parâmetros básicos da busca
        $params = [
            'api_key' => env('TMDB_API_KEY'),
            'query' => $titulo,
            'language' => 'pt-BR'
        ];
        
        // Se o ano foi fornecido, adiciona o filtro de ano
        if ($ano) {
            $params['year'] = $ano; // O TMDB filtra pelo ano de lançamento
        }
        
        // Faz a requisição à API
        $response = Http::withOptions([
            'verify' => base_path('cacert.pem'),
        ])->get('https://api.themoviedb.org/3/search/movie', $params);
        
        // Se não encontrou resultados com ano, tenta sem o ano
        $dados = $response->json();
        if (empty($dados['results']) && $ano) {
            unset($params['year']);
            $response = Http::withOptions([
                'verify' => base_path('cacert.pem'),
            ])->get('https://api.themoviedb.org/3/search/movie', $params);
            $dados = $response->json();
        }
        
        // Retorna apenas os resultados (array de filmes)
        return $dados['results'] ?? [];
    }

    /**
     * COMPARA DOIS TÍTULOS IGNORANDO ACENTOS E MAIÚSCULAS
     * @param string $titulo1 - Primeiro título a comparar
     * @param string $titulo2 - Segundo título a comparar
     * @return bool - Verdadeiro se forem equivalentes
     */
    private function compararTitulos($titulo1, $titulo2)
    {
        if (empty($titulo1) || empty($titulo2)) {
            return false;
        }
        
        $titulo1 = $this->removerAcentos($titulo1);
        $titulo2 = $this->removerAcentos($titulo2);
        
        // Remove caracteres especiais e converte para minúsculas
        $titulo1 = preg_replace('/[^a-z0-9]/i', '', strtolower($titulo1));
        $titulo2 = preg_replace('/[^a-z0-9]/i', '', strtolower($titulo2));
        
        // Verifica se um está contido no outro ou se são iguais
        return strpos($titulo2, $titulo1) !== false || strpos($titulo1, $titulo2) !== false;
    }

    /**
     * COMPARA DOIS NOMES DE GÊNERO
     * Ignora acentos, diferenças de maiúsculas/minúsculas e caracteres especiais
     * @param string $genero1 - Primeiro gênero a comparar
     * @param string $genero2 - Segundo gênero a comparar
     * @return bool - Verdadeiro se forem equivalentes
     */
    private function compararGeneros($genero1, $genero2)
    {
        if (empty($genero1) || empty($genero2)) {
            return false;
        }
        
        // Remove acentos de ambos
        $genero1 = $this->removerAcentos($genero1);
        $genero2 = $this->removerAcentos($genero2);
        
        // Converte para minúsculas e compara
        return strtolower($genero1) === strtolower($genero2);
    }

    /**
     * REMOVE ACENTOS DE UMA STRING
     * Útil para comparar palavras como "Ficção" e "Ficcao"
     * @param string $string - Texto com acentos
     * @return string - Texto sem acentos
     */
    private function removerAcentos($string)
    {
        $acentos = [
            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c',
            'ñ' => 'n',
            'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C',
            'Ñ' => 'N'
        ];
        
        return strtr($string, $acentos);
    }
}