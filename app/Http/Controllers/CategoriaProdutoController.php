<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Servico\CategoriaProdutoServico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaProdutoController extends Controller
{
    private CategoriaProdutoServico $categoriaProdutoServico;

    public function __construct(CategoriaProdutoServico $categoriaProdutoServico)
    {
        $this->categoriaProdutoServico = $categoriaProdutoServico;
    }

    public function cadastrarCategoriaProduto(Request $requisicao): JsonResponse
    {

        return $this->categoriaProdutoServico->salvar($requisicao);
    }

    public function listarTodasCategoriasProduto(): JsonResponse
    {

        return $this->categoriaProdutoServico->listarTodos();
    }

    public function buscarCategoriaProdutoPeloId(string $idHash): JsonResponse
    {

        return $this->categoriaProdutoServico->buscarCategoriaProdutoPeloId($idHash);
    }

    public function alterarStatusCategoriaProduto(Request $requisicao)
    {

        return $this->categoriaProdutoServico->alterarStatusCategoriaProduto($requisicao);
    }

    public function editarCategoriaProduto(Request $requisicao): JsonResponse
    {

        return $this->categoriaProdutoServico->editarCategoriaProduto($requisicao);
    }
}
