<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Produto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProdutoCreateRequest;
use App\Http\Requests\ProdutoUpdateRequest;

class CreateProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importando base de produtos a partir da api fakestoreapi.';


    private $registrosProcessadosComSucesso = 0;
    private $registrosProcessadosComErro = 0;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $idProduto = $this->option('id');

        $this->info(__('messages.products.import.connect'));

        $response = Http::get("https://fakestoreapi.com/products/{$idProduto}");

        if ($response->failed()) {
            $this->error(__('messages.products.import.error'));
            return Command::FAILURE;
        }

        $this->info(__('messages.products.import.open'));

        $produtos = $response->json();

        if ($idProduto) {
            $this->info(__('messages.products.import.start', ['id_produto' => $idProduto]));

            $this->criarProduto($produtos);

            $this->info(__('messages.products.import.success'));
        } else {
            $totalProduto = sizeof($produtos);

            $this->info(__('messages.products.import.start_all', ['total_products' => $totalProduto]));

            $this->criarProdutos($produtos);
        }

        $this->info(__('messages.products.import.total', ['type' => 'sucesso', 'count' => $this->registrosProcessadosComSucesso]));
        $this->info(__('messages.products.import.total', ['type' => 'erro', 'count' => $this->registrosProcessadosComErro]));

        return Command::SUCCESS;
    }

    private function criarProduto($produto)
    {
        $data = [
            'id' => $produto['id'],
            'name' => $produto['title'],
            'price' => $produto['price'],
            'description' => $produto['description'],
            'category' => $produto['category'],
            'image_url' => $produto['image']
        ];

        $request = new ProdutoCreateRequest();
        $validator = Validator::make($data, $request->rules());

        if ($validator->fails()) {
             $this->registrosProcessadosComErro++;
            // $validator->errors()
             //todo: lançar erro em arquivo
        } else {
            $this->registrosProcessadosComSucesso++;
            Produto::updateOrCreate(['id' => $data['id']], $data);
        }
    }

    private function criarProdutos($produtos)
    {
        $bar = $this->output->createProgressBar(sizeof($produtos));
        $bar->start();
        foreach ($produtos as $produto) {
            $this->criarProduto($produto);
            $bar->advance();
        }
        $bar->finish();
    }
}
