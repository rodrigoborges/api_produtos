<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use App\Http\Requests\ProdutoRequest;
use App\Http\Resources\ProdutoResource;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        return ProdutoResource::collection(Produto::listar([
            'search' => $request->search,
            'category' => $request->category,
            'image_url' => $request->image_url,
            'id' => $request->id
        ]));
    }

    public function store(ProdutoRequest $request)
    {
        $produto = Produto::create($request->all());

        if ($produto) {
            return response()->json([
                'success' => true,
                'message' => 'Produto cadatrado com sucesso.',
                'data' => new ProdutoResource($produto)
            ]);
        }

        return response()->json([
                'sucess' => false,
                'message' => 'Erro ao cadastrar produto.',
        ], 404);
    }

    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    public function update(ProdutoRequest $request, Produto $produto)
    {
        $produto->update($request->all());

        return new ProdutoResource($produto);
    }

    public function destroy($id)
    {
        return Produto::destroy($id);
    }
}
