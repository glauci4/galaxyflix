<?php
echo "Teste de execução PHP<br>";

// Verificar se o Laravel carrega
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "Autoload carregado!<br>";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "App carregado!<br>";
    
    // Testar conexão com banco
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel criado!<br>";
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . "<br>";
    echo "Linha: " . $e->getLine() . "<br>";
}