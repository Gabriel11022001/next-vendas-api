<?php

use App\Http\Controllers\CategoriaProdutoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SubCategoriaProdutoController;
use App\Http\Controllers\TabelaPrecoController;
use Illuminate\Support\Facades\Route;

// cadastrar produto
Route::post('/produto', [ ProdutoController::class, 'cadastrarProduto' ]);
// cadastrar categoria de produto
Route::post('/categoria-produto', [ CategoriaProdutoController::class, 'cadastrarCategoriaProduto' ]);
// cadastrar sub-categoria de produto
Route::post('/sub-categoria', [ SubCategoriaProdutoController::class, 'cadastrarSubCategoriaProduto' ]);
// cadastrar tabela de preço
Route::post('/tabela-preco', [ TabelaPrecoController::class, 'cadastrarTabelaPreco' ]);
// editar dados da categoria de produto
Route::put('/categoria-produto', [ CategoriaProdutoController::class, 'editarCategoriaProduto' ]);
// atualizar status da categoria de produto
Route::put('/categoria-produto/status/alterar', [ CategoriaProdutoController::class, 'alterarStatusCategoriaProduto' ]);
// editar sub-categoria do produto
Route::put('/sub-categoria', [ SubCategoriaProdutoController::class, 'editarSubCategoriaProduto' ]);
// alterar o status da tabela de preço
Route::put('/tabela-preco/status/{idHash}', [ TabelaPrecoController::class, 'alterarStatusTabelaPreco' ]);
// alterar status do produto
Route::put('/produto/alterar-status/{idHash}', [ ProdutoController::class, 'alterarStatusProduto' ]);
// buscar todas as categorias
Route::get('/categoria-produto', [ CategoriaProdutoController::class, 'listarTodasCategoriasProduto' ]);
// buscar todas as sub-categorias
Route::get('/sub-categoria', [ SubCategoriaProdutoController::class, 'listarTodasSubCategorias' ]);
// buscar categoria pelo id
Route::get('/categoria-produto/{idHash}', [ CategoriaProdutoController::class, 'buscarCategoriaProdutoPeloId' ]);
// buscar sub-categoria pelo id
Route::get('/sub-categoria/{idHash}', [ SubCategoriaProdutoController::class, 'buscarSubCategoriaPeloId' ]);
// listar todas as tabelas de preço
Route::get('/tabela-preco', [ TabelaPrecoController::class, 'listarTodos' ]);
Route::get('/tabela-preco/{idHash}', [ TabelaPrecoController::class, 'buscarTabelaPrecoPeloId' ]);
// listar todos os produtos
Route::get('/produto', [ ProdutoController::class, 'listarTodosProdutos' ]);
// buscar produto pelo id
Route::get('/produto/{id}', [ ProdutoController::class, 'buscarProdutoPeloId' ]);
