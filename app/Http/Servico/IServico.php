<?php

namespace App\Http\Servico;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface Iservico
{

    function salvar(Request $requisicao): JsonResponse;

    function listarTodos(): JsonResponse;
}
