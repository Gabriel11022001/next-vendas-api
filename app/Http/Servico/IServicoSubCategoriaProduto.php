<?php

namespace App\Http\Servico;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IServicoSubCategoriaProduto extends IServico
{

    function buscarSubCategoriaProdutoPeloId(string $idHash): JsonResponse;

    function editarSubCategoriaProduto(Request $requisicao): JsonResponse;

    function alterarStatusSubCategoriaProduto(Request $requisicao): JsonResponse;
}
