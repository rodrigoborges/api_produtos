<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Produto;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $idProduto = $this->option('id');

        $this->info("Conectando a API fakestoreapi.");

        $response = Http::get("https://fakestoreapi.com/products/{$idProduto}");

        if ($response->failed()) {
            $this->error('Erro ao importar os dados da api fakestoreapi.');
            return Command::FAILURE;
        }

        $this->info("Conexão bem sucedida!");

        $produtos = $response->json();

        $totalProduto = sizeof($produtos);

        $this->info("Iniciando importação de {$totalProduto} produto(s).");

        $this->criarProdutos($produtos);

        return Command::SUCCESS;

    }

    private function criarProdutos($produtos)
    {
        $bar = $this->output->createProgressBar(sizeof($produtos));
        $bar->start();
        foreach ($produtos as $produto) {
            Produto::updateOrCreate(['id' => $produto['id']], [
                'name' => $produto['title'],
                'price' => $produto['price'],
                'description' => $produto['description'],
                'category' => $produto['category'],
                'image_url' => $produto['image']
            ]);
            $bar->advance();
        }
        $bar->finish();
    }
}
