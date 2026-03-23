<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste Laravel</h1>";

// Verificar se arquivos existem
$files = [
    __DIR__ . '/../.env',
    __DIR__ . '/../bootstrap/app.php',
    __DIR__ . '/../vendor/autoload.php',
];

foreach ($files as $file) {
    echo file_exists($file) ? "[OK] " : "[FALHA] ";
    echo $file . "<br>";
}

// Tentar carregar Laravel
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "<br>[OK] Autoload carregado<br>";
    
    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "[OK] App carregado<br>";
    
    echo "[OK] Laravel funcionando!";
} catch (Exception $e) {
    echo "<br>[ERRO] " . $e->getMessage();
    echo "<br>Arquivo: " . $e->getFile();
    echo "<br>Linha: " . $e->getLine();
}
