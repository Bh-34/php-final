<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Http\Requests\CafeRequest;
use Illuminate\Support\Facades\Log;

class CafeController extends Controller
{
    public function listar()
    {
        $consulta = Cafe::query();
        $cafes = $consulta->get();
        return ['message' => 'Listando cafés', 'data' => $cafes];
    }

    public function criar(CafeRequest $request)
    {
        $validacao = $request->all();
        $cafe = new Cafe;
        $cafe->nome = $validacao['nome'];
        $cafe->origem = $validacao['origem'];
        $cafe->marca = $validacao['marca'];
        $cafe->preco = $validacao['preco'];
        $cafe->save();
        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('cafes', 'public');
            $validated['imagem'] = $path;
        }
        return ['message' => 'Café criado com sucesso', 'data' => $cafe];
    }

    public function buscar(string $id)
    {
        $cafe = Cafe::find($id);
        return ['message' => 'Detalhes do café', 'data' => $cafe];
    }

    public function excluir(string $id)
    {
        $cafe = Cafe::find($id);
        $cafe->delete();
        return ['message' => 'Deletando ID '. $id .' café'];
    }

    public function comprar($id)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        $fila = \App\Models\Fila::orderBy('posicao')->get();

        // Admins can buy regardless of fila; non-admins must be first in fila
        if (!$user->admin) {
            if ($fila->isEmpty() || $fila[0]->usuario_id !== $user->id) {
                return response()->json(['message' => 'Não é sua vez na fila!'], 403);
            }

            $fila[0]->status = 'em atendimento';
            $fila[0]->save();

            $fila[0]->status = 'finalizado';
            $fila[0]->save();

            // create pedido record
            $cafe = Cafe::find($id);
            $preco = $cafe ? $cafe->preco : 0;
            \App\Models\Pedido::create([
                'usuario_id' => $user->id,
                'cafe_id' => $id,
                'preco' => $preco,
            ]);

            $fila[0]->delete();

            $restantes = \App\Models\Fila::orderBy('posicao')->get();
            $pos = 1;
            foreach ($restantes as $f) {
                $f->posicao = $pos++;
                $f->save();
            }

            Log::info('Usuário '.$user->id.' comprou café '.$id.' em '.now().' (saiu da fila)');

            return response()->json(['message' => 'Compra registrada para o café ID ' . $id . ' e você saiu da fila!'], 200);
        }

        // Admin flow: allow purchase without modifying fila
        $cafe = Cafe::find($id);
        $preco = $cafe ? $cafe->preco : 0;

        // create pedido record
        \App\Models\Pedido::create([
            'usuario_id' => $user->id,
            'cafe_id' => $id,
            'preco' => $preco,
        ]);

        Log::info('Admin '.$user->id.' comprou café '.$id.' em '.now().' (bypass fila)');
        return response()->json(['message' => 'Compra registrada para o café ID ' . $id . ' (admin)'], 200);
    }
}
