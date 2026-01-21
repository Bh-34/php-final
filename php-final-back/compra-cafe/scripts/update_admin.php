<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$u = User::where('nome','admin')->first();
if ($u) {
    $u->email = 'admin3@gmail.com';
    $u->senha_hash = Hash::make('Kauakaue12@@');
    $u->admin = 1;
    $u->save();
    echo "Updated user id: {$u->id}\n";
} else {
    $u = User::create(['nome'=>'admin','email'=>'admin3@gmail.com','senha_hash'=>Hash::make('Kauakaue12@@'),'admin'=>1]);
    echo "Created user id: {$u->id}\n";
}
