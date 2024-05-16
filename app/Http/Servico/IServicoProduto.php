<?php

namespace App\Http\Servico;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IServicoProduto extends IServico
{

    function editarProduto(Request $requisicao): JsonResponse;

    function buscarProdutoPeloId(string $idHash): JsonResponse;

    function alterarStatusProduto(string $idHash): JsonResponse;

    function controlarQuantidadeUnidadesProdutoEstoque(Request $requisicao): JsonResponse;

    function buscarProdutosAbaixoEstoqueMinimo(): JsonResponse;

    function buscarProdutosAcimaEstoqueMaximo(): JsonResponse;

    function buscarProdutosPelaCategoria(string $idHashCategoria): JsonResponse;

    function buscarProdutosPelaSubCategoria(string $idHashSubCategoria): JsonResponse;

    function buscarProdutosPelaTabelaPreco(string $idHashTabelaPreco): JsonResponse;

    function buscarProdutosAtivosOuInativos(bool $ativo): JsonResponse;

    function filtrarProdutos(Request $requisicao): JsonResponse;
}
