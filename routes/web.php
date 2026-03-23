<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmeController;

// Redireciona a raiz para a lista de filmes
Route::get('/', function () {
    return redirect('/filmes');
});

// Rotas CRUD para filmes
Route::resource('filmes', FilmeController::class);

// Rota de debug para verificar conexões e configurações
Route::get('/debug', function() {
    try {
        // Testar conexão com banco
        DB::connection()->getPdo();
        return "Conexão com banco OK!";
    } catch (Exception $e) {
        return "Erro: " . $e->getMessage();
    }
});