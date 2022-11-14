<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ProdutoResource;
use App\Http\Requests\ProdutoCreateRequest;
use App\Http\Requests\ProdutoUpdateRequest;

class ProdutoController extends Controller
{

    public function index(Request $request)
    {
        return ProdutoResource::collection(Produto::listar([
            'search' => $request->search,
            'category' => $request->category,
            'has_image' => $request->has_image,
            'id' => $request->id
        ]));
    }

    public function store(ProdutoCreateRequest $request)
    {
        $produto = Produto::create($request->all());

        if ($produto) {
            return new ProdutoResource($produto);
        }

        return response()->json(['message' => __('messages.products.error')], Response::HTTP_BAD_REQUEST);
    }

    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    public function update(ProdutoUpdateRequest $request, Produto $produto)
    {
        $produto->update($request->all());

        return new ProdutoResource($produto);
    }

    public function destroy(Produto $produto)
    {
        try {
            $produto->delete();
        } catch (\Exception $e) {
            return response()->json(null, Response::HTTP_BAD_REQUEST);
        }

        return ProdutoResource::make($produto);

    }
}
