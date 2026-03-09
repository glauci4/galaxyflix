<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 👈 ADICIONE ESTA LINHA
use Illuminate\Database\Eloquent\Model;

class Filme extends Model
{
    use HasFactory;

    protected $fillable = [
    'titulo',
    'genero',
    'ano',
    'poster',
    'sinopse',
    'nota'
];
}