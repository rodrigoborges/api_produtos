<?php

return [
    'errors' => [
        'validation' => 'Erros de validação.'
    ],
    'products' => [
        'error' => 'Erro ao criar um produto.',
        'import'=> [
            'connect' => 'Conectando a API fakestoreapi.',
            'error' => 'Erro ao importar os dados da api fakestoreapi.',
            'open' => 'Conexão bem sucedida!',
            'start' => "Iniciando importação do produto id :id_produto.",
            'empty' => "Não existe produto de ID :id_produto a ser importado.",
            'success' => "Importação concluída com sucesso.",
            'start_all' => 'Iniciando importação de :total_products produto(s).',
            'total' => 'Total de registros processados com :type: :count',
        ]
    ],
];
