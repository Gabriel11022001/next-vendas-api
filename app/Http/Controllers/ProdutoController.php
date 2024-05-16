<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Servico\ProdutoServico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private ProdutoServico $produtoServico;

    public function __construct(ProdutoServico $produtoServico)
    {
        $this->produtoServico = $produtoServico;
    }

    public function cadastrarProduto(Request $requisicao): JsonResponse
    {

        return $this->produtoServico->salvar($requisicao);
    }

    public function listarTodosProdutos(): JsonResponse
    {

        return $this->produtoServico->listarTodos();
    }

    public function buscarProdutoPeloId(int $idProdutoRelacionamentoTabelaPreco): JsonResponse
    {

        return $this->produtoServico->buscarProdutoRelacionamentoTabelaPreco($idProdutoRelacionamentoTabelaPreco);
    }

    public function alterarStatusProduto(string $idHash): JsonResponse
    {

        return $this->produtoServico->alterarStatusProduto($idHash);
    }
}
