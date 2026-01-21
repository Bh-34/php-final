<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // List pedidos: admins see all, others see only their pedidos
    public function listar(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        if ($user->admin) {
            $pedidos = Pedido::with(['usuario', 'cafe'])->orderBy('created_at', 'desc')->get();
        } else {
            $pedidos = Pedido::with(['cafe'])->where('usuario_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        return response()->json(['message' => 'Lista de pedidos', 'data' => $pedidos], 200);
    }

    public function buscar($id)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        $pedido = Pedido::with(['usuario', 'cafe'])->find($id);
        if (!$pedido) return response()->json(['message' => 'Pedido não encontrado'], 404);

        // only allow owner or admin
        if (!$user->admin && $pedido->usuario_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        return response()->json(['message' => 'Pedido encontrado', 'data' => $pedido], 200);
    }

    public function criar(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user || !$user->admin) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $request->validate([
            'usuario_id' => 'required|integer',
            'cafe_id' => 'required|integer',
            'preco' => 'required|numeric',
        ]);

        $pedido = Pedido::create([
            'usuario_id' => $request->usuario_id,
            'cafe_id' => $request->cafe_id,
            'preco' => $request->preco,
        ]);

        return response()->json(['message' => 'Pedido criado', 'data' => $pedido], 201);
    }
}
