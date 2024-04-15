<?php

namespace App\Http\Servico;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IServicoCategoriaProduto extends Iservico
{

    function buscarCategoriaProdutoPeloId(string $idHash): JsonResponse;

    function editarCategoriaProduto(Request $requisicao): JsonResponse;

    function alterarStatusCategoriaProduto(Request $requisicao): JsonResponse;
}
