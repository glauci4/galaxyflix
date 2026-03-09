<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmeController;

// Redireciona a raiz para a lista de filmes
Route::get('/', function () {
    return redirect('/filmes');
});

// Rotas CRUD para filmes
Route::resource('filmes', FilmeController::class);