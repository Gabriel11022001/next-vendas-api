<?php

use App\Http\Controllers\CategoriaProdutoController;
use App\Http\Controllers\SubCategoriaProdutoController;
use Illuminate\Support\Facades\Route;

Route::post('/categoria-produto', [ CategoriaProdutoController::class, 'cadastrarCategoriaProduto' ]);
Route::post('/sub-categoria', [ SubCategoriaProdutoController::class, 'cadastrarSubCategoriaProduto' ]);
Route::put('/categoria-produto/status/alterar', [ CategoriaProdutoController::class, 'alterarStatusCategoriaProduto' ]);
Route::get('/categoria-produto', [ CategoriaProdutoController::class, 'listarTodasCategoriasProduto' ]);
Route::get('/sub-categoria', [ SubCategoriaProdutoController::class, 'listarTodasSubCategorias' ]);
Route::get('/categoria-produto/{idHash}', [ CategoriaProdutoController::class, 'buscarCategoriaProdutoPeloId' ]);
Route::get('/sub-categoria/{idHash}', [ SubCategoriaProdutoController::class, 'buscarSubCategoriaPeloId' ]);
