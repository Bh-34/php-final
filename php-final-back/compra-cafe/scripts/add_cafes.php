<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cafe;

$c1 = new Cafe();
$c1->nome = 'Arábica Supremo';
$c1->marca = 'Café Brasil';
$c1->descricao = 'Grãos 100% arábica, torra média, aroma intenso.';
$c1->preco = 19.90;
$c1->quantidade = 10;
$c1->imagem_url = 'https://via.placeholder.com/150';
$c1->save();

$c2 = new Cafe();
$c2->nome = 'Robusta Forte';
$c2->marca = 'Café Ceará';
$c2->descricao = 'Blend robusta com sabor forte e corpo encorpado.';
$c2->preco = 15.50;
$c2->quantidade = 20;
$c2->imagem_url = 'https://via.placeholder.com/150';
$c2->save();

echo "Inserted cafes: {$c1->id} ({$c1->nome}), {$c2->id} ({$c2->nome})\n";
