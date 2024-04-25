<?php

namespace App\Http\Servico;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IServicoTabelaPreco extends IServico
{

    function buscarTabelaPrecoPeloId(string $idHash): JsonResponse;

    function alterarStatusTabelaPreco(string $idHash): JsonResponse;

    function editarTabelaPreco(Request $requisicao): JsonResponse;

    function atribuirProdutoTabelaPreco(Request $requisicao): JsonResponse;

    function removerProdutoTabelaPreco(Request $requisicao): JsonResponse;
}
