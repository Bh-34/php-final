<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $fillable = [
        'usuario_id',
        'cafe_id',
        'preco',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function cafe()
    {
        return $this->belongsTo(Cafe::class, 'cafe_id');
    }
}
