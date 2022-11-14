<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'image_url',
    ];

    public static function listar(array $filtros = []): Collection
    {
        $query = Produto::query()
            ->when(isset($filtros['search']), function ($q) use ($filtros) {
                $q->where('name', 'ilike', '%' . $filtros['search'] . '%')->orWhere('category', 'ilike', '%' . $filtros['search'] . '%');
            })
            ->when(isset($filtros['category']), function ($q) use ($filtros) {
                $q->where('category', $filtros['category']);
            })
            ->when(isset($filtros['image_url']), function ($q) use ($filtros) {
                if ($filtros['image_url']) {
                    $q->whereNotNull('image_url');
                } else {
                    $q->whereNull('image_url');
                }

            })
            ->when(isset($filtros['id']), function ($q) use ($filtros) {
                $q->where('id', '=', $filtros['id']);
            })
            ->orderBy('name');

        $dados = $query->get();

        return $dados;
    }

}
