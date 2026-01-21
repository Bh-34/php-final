<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$u = User::updateOrCreate(
    ['email' => 'admin3@gmail.com'],
    ['nome' => 'admin', 'senha_hash' => Hash::make('Kauakaue12@@'), 'admin' => 1]
);

echo "User id: {$u->id}\n";
